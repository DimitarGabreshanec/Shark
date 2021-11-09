<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'admin':
                        $redirect_url = RouteServiceProvider::ADMIN_HOME;
                        break;
                    case 'store':
                        $redirect_url = RouteServiceProvider::STORE_HOME;
                        break;
                    default:
                        $redirect_url = RouteServiceProvider::HOME;
                        break;
                }
                return redirect($redirect_url);
            }
        }

        return $next($request);
    }
}
