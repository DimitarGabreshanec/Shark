<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Providers\RouteServiceProvider;
use App\Service\StoreService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::STORE_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:store')->except('logout');
    }

    public function showLoginForm(Request $request)
    { 
        return view("store.auth.login.login");
    }

    protected function validateLogin(Request $request)
    {
        $message = [
            'email.required' => 'メールアドレスを入力してください。',
            'password.required' => 'パスワードを入力してください。', 
        ];

        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], $message);
    }

    public function login(Request $request)
    { 
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
 
        if ($this->attemptLogin($request)) {   
            $store = Store::where('email',  $request->input('email'))->first();
            $store->last_login_at = Carbon::now()->format('Y-m-d H:i:s');
            $store->save();
            return $this->sendLoginResponse($request);
        }  

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function credentials(Request $request)
    {
        $login_params = $request->only($this->username(), 'password');
        return array_merge($login_params, ['status'=>config('const.store_status_code.registered')]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect(route('store.login.before'));
    }

    public function loginBefore(Request $request)
    { 
        return redirect()->route('store.login.form');
    }

    protected function guard()
    {
        return Auth::guard('store');
    }
 
}
