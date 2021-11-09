<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'web')
    {
        // 有効な署名付きURLはリダイレクト対象外
        if (!$request->hasValidSignature()) {
            switch ($guard) {
                case 'admin':
                    $redirect_url = '/admin/login';
                    break;
                case 'store':
                    $redirect_url = '/store/login';
                case 'api':
                    $redirect_url = '/api/unauthorized';
                    break;
                default:
                    $redirect_url = '/login';
                    break;
            }
            if (!Auth::guard($guard)->check()) {
                return redirect($redirect_url);
            }
        }
        return $next($request);
    }

}
