<?php

namespace App\Http\Controllers\User\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Auth;
use Carbon\Carbon;
use Socialite;

class SocialController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:web');
    }

    /**
     * Redirects to appropriate providers based on
     * $provider
     *
     * @param string $provider
     * @return RedirectResponse
     */
    protected $providers = [
        'facebook', 'google', 'apple', 'line'
    ];

    public function redirectToProvider($driver)
    {
        if (!$this->isProviderAllowed($driver)) {
            return $this->sendFailedResponse(ucfirst($driver) . "は現在サポートされていません。");
        }

        try {
            return Socialite::driver($driver)->redirect();

        } catch (Exception $e) {
            // You should show something simple fail message
            return $this->sendFailedResponse($e->getMessage());
        }
    }


    public function handleProviderCallback($driver)
    {

        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        // check for email in returned user
        return empty($user->email)
            ? $this->sendFailedResponse(cfirst($driver) . "からメールが返されませんでした。")
            : $this->loginOrCreateAccount($user, $driver);
    }

    protected function sendSuccessResponse()
    {
        return redirect()->route('user.stores.imasugu');
    }

    protected function sendFailedResponse($msg = null)
    {
        return redirect()->route('user.login.before')
            ->withErrors(['msg' => $msg ?: 'ログインすることができません。他のプロバイダにログインしてみてください。']);
    }

    protected function loginOrCreateAccount($providerUser, $driver)
    {
        $user = User::where('email', $providerUser->getEmail())->first();

        if(!is_object($user)) {
            // check for already has account
            $user = User::where([
                'social->' . $driver . '->id' => $providerUser->id,
            ])->first();
        }

        // if user already found
        if ($user) {
            // update the avatar and provider that might have changed
            $user->social = [
                $driver => [
                    'id' => $providerUser->getId(),
                    'token' => $providerUser->token,
                    'avatar' => $providerUser->getAvatar(),
                ]
            ];
            $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
        } else {
            // create a new user
            $user = User::create([
                'member_no' => UserService::generateUserCode(),
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'status' => config('const.user_status_code.registered'),
                'last_login_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'social' => [
                    $driver => [
                        'id' => $providerUser->getId(),
                        'token' => $providerUser->token,
                        'avatar' => $providerUser->getAvatar(),
                    ],
                ],

                // user can use reset password to create a password
                'password' => ''
            ]);
        }

        // login the user
        Auth::login($user, true);

        return $this->sendSuccessResponse();
    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}
