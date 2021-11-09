<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Service\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Session;
use Str;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }

    public function loginBefore(Request $request)
    {
        if($request->has('from_sp')) {
            Session::put('from_sp', $request->input('from_sp', 0)); 
            //return view("user.auth.login.thanks");
        } 
        return view("user.auth.login.before");
    }

    public function showLoginForm(Request $request)
    {
        return view("user.auth.login.login");
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
            $user = User::where('email',  $request->input('email'))->first();
            $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            $is_from_sp = false;
            if(Session::has('from_sp')) {
                $is_from_sp = true;
            }
            return $this->sendLoginResponse($request, $is_from_sp);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request, $is_from_sp)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        if($is_from_sp) {
            return redirect()->route('user.sp.login_after');
        }
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    protected function credentials(Request $request)
    {
        $login_params = $request->only($this->username(), 'password');
        return array_merge($login_params, ['status'=>config('const.user_status_code.registered')]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        if($request->has('login_token') && $request->has('member_no')) {
            $auth_user = User::where('member_no', $request->input('member_no'))
                ->where('api_token', $request->input('login_token'))
                ->first();
            if(is_object($auth_user)) {
                return $this->loggedOut($request) ?: redirect(route('user.auth.login.sp_login'));
            }
        }
        return $this->loggedOut($request) ?: redirect(route('user.login.before'));
    }

    public function loginFromSP(Request $request)
    {
        if($request->has('login_token') && $request->has('member_no')) {
            $auth_user = User::where('member_no', $request->input('member_no'))
                ->where('api_token', $request->input('login_token'))
                ->first();
            if(is_object($auth_user)) {
                Auth::login($auth_user, true);
                return redirect()->route('user.stores.imasugu');
            }
        }
        return view("user.auth.login.sp_login");
    }

    protected function guard()
    {
        return Auth::guard('web');
    }
}
