<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        Log::info('RedirectIfAuthenticated middleware started.'); // 開始ログ

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Log::info('Auth check passed for guard: ' . $guard);

                if (!$request->route()) {
                    Log::warning('Request route is null.');
                    return redirect('/login'); // デフォルトのリダイレクト先
                }

                if (!$request->route()->getName()) {
                    Log::warning('Route name is null.');
                } else {
                    Log::info('Route name: ' . $request->route()->getName());
                }

                if ($request->route()->getName() !== 'login') {
                    Log::info('Redirecting to products.index.');
                    return redirect()->route('products.index');
                }
            } else {
                Log::info('Auth check failed for guard: ' . $guard);
            }
        }

        Log::info('Passing to the next middleware.');
        return $next($request);
    }
}
