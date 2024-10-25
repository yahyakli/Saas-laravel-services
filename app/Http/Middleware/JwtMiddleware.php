<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized - No Token Provided'], 401);
        }

        try {
            // Decode the base64-encoded secret key
            $secretKey = base64_decode(env('JWT_SECRET'));

            // Verify the token with the decoded secret and HS256 algorithm
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            
            $request->user = $decoded; // Attach the decoded data to the request

        } catch (Exception $e) {
            return response()->json(['message' => 'Forbidden - Invalid or Expired Token'], 403);
        }

        return $next($request);
    }
}
