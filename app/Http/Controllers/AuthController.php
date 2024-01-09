<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Response;
use App\Models\Credential;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $payload = null;
    private $algo = "HS256";

    private function generateToken()
    {
        if (!($this->payload instanceof Credential)) return Response::message("Invalid Payload", 401);

        $payload = $this->payload->toArray();
        $time = time();
        $expAccess = $time + 7200;
        $keyAccess = env("JWT_SECRET");
        $payloadAccess = $payload + ["exp" => $expAccess];
        $accessToken = JWT::encode($payloadAccess, $keyAccess, $this->algo);

        $keyRefresh = env("JWT_REFRESH");
        $expRefresh = $time + 604800;
        $payloadRefresh = $payload + ["exp" => $expRefresh];
        $refreshToken = JWT::encode($payloadRefresh, $keyRefresh, $this->algo);

        return [
            "token_access"  => $accessToken,
            "token_refresh" => $refreshToken,
            "payload"       => $payload
        ];
    }

    public function token(Request $request)
    {
        $responseError = Response::message("Username atau Password salah!", 400);
        $credential = Credential::where("username", $request->username)->first();
        if (!$credential) return $responseError;

        $isVerifyPass = password_verify($request->password, $credential->password);
        if (!$isVerifyPass) return $responseError;

        $this->payload = $credential;
        $token = $this->generateToken();

        return Response::success($token);
    }

    public function refreshToken(Request $request)
    {
        if (!$request->token_refresh) {
            return Response::message("Token Refresh Required", 400);
        }

        try {
            $key = env("JWT_REFRESH");
            $payload = JWT::decode($request->token_refresh, new Key($key, $this->algo));

            $credential = Credential::where("username", $payload->username)->first();
            $this->payload = $credential;
            $token = $this->generateToken();

            return Response::success($token);
        } catch (Exception $e) {
            return Response::message($e->getMessage(), 500);
        }
    }
}
