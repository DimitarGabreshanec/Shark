<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/store/password/completed';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:store');
    }

    public function showResetForm(Request $request, $token = null)
    {  
        return view("store.auth.passwords.reset")->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function resetPassword($store, $password)
    {
        $this->setUserPassword($store, $password);

        $store->setRememberToken(Str::random(60));

        $store->save();

        event(new PasswordReset($store));
    }

    public function completed(Request $request)
    { 
        $request->session()->flash('success', 'パスワードが変更されました。');
        return view("store.auth.passwords.completed");
    }

    public function broker()
    {
        return Password::broker('stores');
    }
}
