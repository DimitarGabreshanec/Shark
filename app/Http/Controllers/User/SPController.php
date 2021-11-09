<?php

namespace App\Http\Controllers\User;
use App\Http\Requests\User\UserDataRequest;
use App\Http\Requests\User\MailRequest;
use App\Http\Requests\User\PasswordRequest;
use App\Service\PaymentService;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;
use Str;

class SPController extends UserController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function spLoginAfter(Request $request)
    {
        $user = Auth::user();
        $result['member_no'] =  $user->member_no;
        $tokenResult = $user->createToken('campbell');
        $token = $tokenResult->token;
        $result['pass_token'] = $tokenResult->accessToken;
        $user->api_token = Str::random(128);
        $user->save();
        $token->save();
        $result['login_token'] =  $user->api_token;
        return "<script> var document_cookie = '". json_encode($result) . "'; </script>";
    }
 

}
