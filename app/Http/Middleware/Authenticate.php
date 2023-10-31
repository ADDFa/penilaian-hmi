<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Http\Response;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return Response::message("Auth Failed", 401);
            }

            $keyAccess = env("JWT_SECRET");
            $decoded = JWT::decode($token, new Key($keyAccess, "HS256"));
            $request->payload = $decoded;

            return $next($request);
        } catch (SignatureInvalidException $e) {
            return Response::message($e->getMessage(), 400);
        } catch (ExpiredException $e) {
            return Response::message($e->getMessage(), 400);
        } catch (Exception $e) {
            return Response::message($e->getMessage(), 500);
        }
    }
}
