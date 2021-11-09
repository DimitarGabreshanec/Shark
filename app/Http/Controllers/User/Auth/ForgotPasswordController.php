<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Auth;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest:web');
    }

    public function showLinkRequestForm(Request $request)
    { 
        return view("user.auth.passwords.email");
    }

    protected function sendResetLinkResponse(Request $request, $response)
    { 
        return redirect()->route('user.password.sent_mail');
    }

    public function sentMail()
    {
        return view("user.auth.passwords.sent_mail");
    }

    public function broker()
    {
        return Password::broker('users');
    }
}
