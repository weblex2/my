<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JwtAuth
{
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();

        if (!$jwt) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWT::decode($jwt, new Key(env('JWT_SECRET'), 'HS256'));
            $user = User::find($decoded->sub);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 401);
            }

            Auth::setUser($user); // Setze den authentifizierten Benutzer
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
