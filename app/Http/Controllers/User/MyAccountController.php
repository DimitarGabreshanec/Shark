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

class MyAccountController extends UserController
{
    public function __construct()
    {
        //$this->f_menu = config('const.footer_menu_kind_en.store');
        parent::__construct();
    }

    public function index(Request $request)
    {
        return view("user.my_account.index", [
            // 'stores' => $stores
        ]);
    }

    public function info(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();
        return view("user.my_account.user_info", [
            'user' => $user,
        ]);
    }

    public function edit(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();
        $birthday = $user['birthday'];
        $birth_year = Carbon::parse($user->birthday)->format('Y');
        $birth_month = Carbon::parse($user->birthday)->format('m');
        $birth_day = Carbon::parse($user->birthday)->format('d');
        return view("user.my_account.user_edit", [
            'user' => $user,
            'index_params' => $index_params,
            'birth_year' => $birth_year,
            'birth_month' => $birth_month,
            'birth_day' => $birth_day,
        ]);
    }

    public function passwordUpdate(PasswordRequest $request)
    {
        $user = Auth::user();
        $index_params = $request->all();

        if ($user->update(['password'=> Hash::make($request->password)])) {
            $user->sendPasswordUpdateNotification($request->password);
            $request->session()->flash('success', 'パスワードを更新しました。');
        } else {
            $request->session()->flash('error', 'パスワードが失敗しました。');
        }
        return redirect()->route('user.my_account.password_edit');
    }

    public function mailUpdate(MailRequest $request)
    {
        $user = Auth::user();
        $index_params = $request->all();
        $old_email = $user->email;
        if ($user->update(['email'=> $request->email])) {

            $user->sendMailUpdateNotification($old_email, $request->email);
            $request->session()->flash('success', 'メールアドレスを更新しました。');
        } else {
            $request->session()->flash('error', 'メールアドレス更新が失敗しました。');
        }
        return redirect()->route('user.my_account.mail_edit');
    }

    public function update(UserDataRequest $request)
    {
        $user = Auth::user();
        $index_params = $request->all();
        if (UserService::doUpdateFront($user, $index_params)) {
            $request->session()->flash('success', '基本情報を更新しました。');
        } else {
            $request->session()->flash('error', '基本情報更新が失敗しました。');
        }

        return redirect()->route('user.my_account.info');
    }

    public function mailEdit(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();
        $request->session()->flash('email', $user['email']);
        return view("user.my_account.mail_edit", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function passwordEdit(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account.password_edit", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function _Edit(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account._edit", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function cardDetail(Request $request)
    {
        $user = Auth::user();
        if(!isset($user->gmo_card_info)) {
            return redirect()->route('user.my_account.card_form');
        }
        return view("user.my_account.card_info", [
            'user' => $user,
            'card_info' => $user->gmo_card_info,
        ]);
    }

    public function cardForm(Request $request)
    {
        $user = Auth::user();

        return view("user.my_account.card_form", [
            'user' => $user,
        ]);
    }

    public function setCard(Request $request)
    {
        $user = Auth::user();
        $obj_gmo = new PaymentService();

        $member_id = $obj_gmo->getMemberByUser($user);

        if($member_id) {
            $user->gmo_member_id = $member_id;
            if($user->gmo_card_info) {
                $gmo_card = $obj_gmo->updateCard($request->input('card_token'), $member_id, $user->gmo_card_info->CardSeq);
            } else {
                $gmo_card = $obj_gmo->registerCard($request->input('card_token'), $member_id);
            }
            if(isset($gmo_card['CardNo'])) {
                $user->gmo_card_info = $gmo_card;
                $user->save();
                $request->session()->flash('success', 'カード情報を登録しました。');
                return redirect()->route('user.my_account.card_detail');
            }
        }
        $request->session()->flash('error', 'カード情報登録が失敗しました。');
        return back();
    }

    public function FlowOfUse(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account.flow_of_use", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function faq(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account.faq", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function termsOfService(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account.terms_of_service", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function privacyPolice(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account.privacy_police", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function contactUs(Request $request)
    {
        $index_params = $request->all();
        $user = Auth::user();

        return view("user.my_account.contact_us", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }


}
