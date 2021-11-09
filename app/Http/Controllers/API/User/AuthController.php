<?php

namespace App\Http\Controllers\API\User;

use Str;
use Validator;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Session;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        Log::info("API Login: " .  print_r($request->all(), true));
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();

            $success['member_no'] =  $user->member_no;

            
            $tokenResult = $user->createToken('campbell');
            $token = $tokenResult->token;
            $success['pass_token'] = $tokenResult->accessToken;
            $user->api_token = Str::random(128);
            $user->save();
            $token->save();
            $success['login_token'] =  $user->api_token;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorized', ['error'=>'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function unauthorized(Request $request)
    {
        return $this->sendError('Unauthorized', ['error'=>'Unauthorized'], 401);
    }

    public function saveFirebaseToken (Request $request) {
        $user = Auth::guard('api')->user();
        $user->firebase_token = $request->input('firebase_token');
        $user->save();
        $success['firebase_token'] =  $user->firebase_token ;
        return $this->sendResponse($success, 'Saved firebase token successfully.');
    }  

    public function productInfo(Request $request, Store $store, Product $product)
    {
        return view("user.store.product_info", [
            'store' => $store,
            'product' => $product,
        ]);
    }


}
