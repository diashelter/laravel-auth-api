<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateJWTiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($request->header('authorization') === null) {
                throw new \Exception("Token is required");
            }
            $token = str_replace('Bearer ', '', $request->header('authorization'));
            $key = 'example_key';
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $request->attributes->set('user', $decoded->user);
            return $next($request);
        } catch (SignatureInvalidException $exception) {
            Log::error($exception->getMessage());
            return new JsonResponse(['message' => "Unauthenticated."], 401);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return new JsonResponse(['message' => "Unauthenticated."], 401);
        }
    }
}
