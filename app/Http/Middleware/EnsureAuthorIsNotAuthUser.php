<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAuthorIsNotAuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->route('post')->user;
        if (auth()->id() === $user->id) {
            $error = 'Ensure user is not auth user failed.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $error], 400);
            }
            return back()->with('error', $error);
        }
        return $next($request);
    }
}
