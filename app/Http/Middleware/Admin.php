<?php

namespace App\Http\Middleware;

use App\Services\AdminService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

class Admin
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
        if (Auth::check() && $request->user()->is_admin) {
            $service = new AdminService;
            View::share('adminModels', $service->getAdminModelsWithListCreateActions());
            return $next($request);
        }

        abort(403);
    }
}
