<?php

namespace App\Http\Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Response;
use App\Models\Credential;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\SignatureInvalidException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username"  => "required",
            "password"  => "required"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $message = "Username atau Password Salah!";
        $credential = Credential::where("username", $request->username)->first();
        if (!$credential) {
            return Response::message($message, 400);
        }

        $isValidPassword = password_verify($request->password, $credential->password);
        if (!$isValidPassword) {
            return Response::message($message, 400);
        }

        $payload = $credential->toArray();
        $result = $this->generateCode($payload);
        return Response::success($result);
    }

    public function refresh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "token" => "required"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        try {
            $keyRefresh = env("JWT_REFRESH");
            $decoded = JWT::decode($request->token, new Key($keyRefresh, "HS256"));
            $payload = Credential::find($decoded->id)->toArray();

            $result = $this->generateCode($payload);
            return Response::success($result);
        } catch (SignatureInvalidException $e) {
            return Response::message("Token invalid! " . $e->getMessage(), 400);
        } catch (ExpiredException $e) {
            return Response::message("Token Expired!" . $e->getMessage(), 400);
        } catch (Exception $e) {
            return Response::message($e->getMessage(), 500);
        }
    }

    private function generateCode(array $payload)
    {
        $algo = "HS256";
        $time = time();
        $expAccess = $time + 7200;
        $expRefresh = $time + 604800;

        $payloadAccess = $payload + ["exp" => $expAccess];
        $payloadRefresh = $payload + ["exp" => $expRefresh];

        $keyAccess = env("JWT_SECRET");
        $keyrefresh = env("JWT_REFRESH");

        $tokenAccess = JWT::encode($payloadAccess, $keyAccess, $algo);
        $tokenRefresh = JWT::encode($payloadRefresh, $keyrefresh, $algo);

        return [
            "token_access"      => $tokenAccess,
            "token_refresh"     => $tokenRefresh,
            "payload"           => $payload
        ];
    }
}
