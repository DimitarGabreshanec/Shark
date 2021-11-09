<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Notifications\User\VerifyEmailCustom;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Service\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Service\FirebaseService;
use Session; 

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register/sent_mail';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function registerBefore(Request $request)
    {
        return view("user.auth.register.before");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'email.required' => 'メールアドレスを入力してください。',
            'email.unique' => 'すぐに登録されたメールアドレスです。',
        ];
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
        ], $message);
    }

    //verify email
    public function showRegisterForm(Request $request)
    {    
        return view("user.auth.register.email");
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
         $user = User::create([
            'email' => $data['email'],
            'email_verify_token' => base64_encode($data['email']),
            'firebase_token' => $data['firebase_token']
        ]); 

        $user->notify(new VerifyEmailCustom());
        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));


        if ($response = $this->registered($request, $user)) { 
            return $response;
        }  
        // if(Session::has('from_sp')) {
        //     return redirect()->route('user.sp.register_after');
        // }

        return $request->wantsJson() ? new JsonResponse([], 201) : redirect($this->redirectPath());
    }

    //sent mail
    public function sentMail(Request $request)
    { 

        return view("user.auth.register.sent_mail");
    }

    //user form
    public function showUserForm(Request $request)
    {
        $user = User::where('email_verify_token', $request->input('token'))
            ->where('status', '!=', config('const.user_status_code.registered'))
            ->whereNotNull('firebase_token')
            ->first();
         
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');   
        
        if(!$user->save()){
            abort(404);
        }
        $from_sp = $request->input('from_sp'); 
         
        if($from_sp == 1){ 
            $firebaseTokens = $user->firebase_token;  
            $url = route('user.register.user_info', ['token'=>$request->input('token'), 'from_sp'=>0]);
            if(empty($firebaseTokens) || $firebaseTokens == null){
                abort(405);
            }
            FirebaseService::handleCreateUser($firebaseTokens, $user->email, $url);
            return view("user.auth.login.thanks");
        } else {
            return view("user.auth.register.user_form", [
                'user' => $user,
            ]);
        }
        
    }

    //user form
    public function registerUserData(UserRequest $request, User $user)
    { 
        $use_params = $request->all();
        $use_params['status'] = config('const.user_status_code.registered'); 
        $use_params['member_no'] = UserService::generateUserCode();
        if(UserService::doUpdateFront($user, $use_params)) {
            $request->session()->flash('success', 'ユーザー登録が完了しました。'); 
            return redirect()->route('user.register.complete');
        } else {
            $request->session()->flash('error', '会員登録が失敗しました。');
            return back();
        }
    }

    public function complete(Request $request)
    {
        return view("user.auth.register.complete");
    }

}
