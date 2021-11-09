<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\LoginStoreRequest;
use App\Notifications\Store\VerifyEmailCustom;
use App\Providers\RouteServiceProvider;
use App\Models\Store;
use App\Service\StoreService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DateTime;

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
        $this->middleware('guest:store');
    }

    public function registerBefore(Request $request)
    {
        return view("store.auth.register.before");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
        ]);
    }

    //verify email
    public function showRegisterForm(Request $request)
    {   
        return view("store.auth.register.email");
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    { 
         $store = Store::create([
            'email' => $data['email'], 
            'email_verify_token' => base64_encode($data['email']), 
        ]);
 

        $store->notify(new VerifyEmailCustom());
        return $store;
    }

    public function register(Request $request)
    { 
        $this->validator($request->all())->validate();

        event(new Registered($store = $this->create($request->all())));

        if ($response = $this->registered($request, $store)) {
            return $response;
        }

        return $request->wantsJson() ? new JsonResponse([], 201) : redirect($this->redirectPath());
    }

    //sent mail
    public function sentMail(Request $request)
    {  
        return view("store.auth.register.sent_mail");
    }

    //user form
    public function showUserForm(Request $request)
    {
        $store = Store::where('email_verify_token', $request->input('token'))
            ->where('status', '!=', config('const.store_status_code.registered'))
            ->first();
        $store->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        if(!$store->save()){
            abort(404);
        }
        return view("store.auth.register.store_form", [
            'store' => $store,
        ]);
    }

    //store form
    public function registerStoreData(LoginStoreRequest $request, Store $store)
    {   
        $use_params = $request->all();
        $uploaded_file = $request->file('main_image');  
        if($uploaded_file != null){
            $now = DateTime::createFromFormat('U.u', microtime(true)); 
            $new_filename = "main_".$now->format("YmdHisu"). '.'. $uploaded_file->getClientOriginalExtension(); 
            $uploaded_file->storeAs('public/temp', $new_filename); 
            $use_params['main_img'] = $new_filename; 
        } 
        $use_params['status'] = config('const.store_status_code.registered');
        $use_params['store_no'] = StoreService::generateStoreCode();
        if(StoreService::doUpdateFront($store, $use_params)) {
            $request->session()->flash('success', 'ユーザー登録が完了しました。'); 
            return redirect()->route('store.register.complete');
        } else {
            $request->session()->flash('error', '会員登録が失敗しました。');
            return back();
        } 
    }

    public function complete(Request $request)
    {
        return view("store.auth.register.complete");
    }

}
