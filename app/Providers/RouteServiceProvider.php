<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/shop/imasugu/map';
    public const SP_HOME = '/sp_login/after';
    public const STORE_HOME = '/store/my_account/info';
    public const ADMIN_HOME = '/admin';
    public const USER_API = '/api';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespaceUser = 'App\Http\Controllers\User';
    protected $namespaceStore = 'App\Http\Controllers\Store';
    protected $namespaceAdmin = 'App\Http\Controllers\Admin';
    protected $namespaceUserApi = 'App\Http\Controllers\API\User';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespaceUserApi)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespaceUser)
                ->group(base_path('routes/web.php'));

            Route::middleware('store')
                ->prefix('store')
                ->namespace($this->namespaceStore)
                ->group(base_path('routes/store.php'));

            Route::middleware('admin')
                ->prefix('admin')
                ->namespace($this->namespaceAdmin)
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
