<?php

namespace App\Service;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private $p_site_id;
    private $p_site_pass;
    private $p_shop_id;
    private $p_shop_pass;
    private $p_entry_tran_url;
    private $p_exec_tran_url;
    private $p_alter_tran_url;
    private $p_secure_verify_url;
    private $p_change_tran_url;
    private $p_save_card_url;
    private $p_delete_card_url;
    private $p_search_card_url;
    private $p_traded_card_url;
    private $p_save_member_url;
    private $p_delete_member_url;
    private $p_search_member_url;
    private $p_update_member_url;
    private $p_search_trade_url;

    public function __construct()
    {
        $this->p_site_id = config('payment.gmo_site_id');
        $this->p_site_pass = config('payment.gmo_site_pass');
        $this->p_shop_id = config('payment.gmo_shop_id');
        $this->p_shop_pass = config('payment.gmo_shop_pass');
        $this->p_entry_tran_url = config('payment.entry_tran_url');
        $this->p_exec_tran_url = config('payment.exec_tran_url');
        $this->p_alter_tran_url = config('payment.alter_tran_url');
        $this->p_secure_verify_url = config('payment.secure_tran_url');
        $this->p_change_tran_url = config('payment.change_tran_rul');
        $this->p_save_card_url = config('payment.save_card_url');
        $this->p_delete_card_url = config('payment.delete_card_url');
        $this->p_search_card_url = config('payment.search_card_url');
        $this->p_traded_card_url = config('payment.traded_card_url');
        $this->p_save_member_url = config('payment.save_member_url');
        $this->p_delete_member_url = config('payment.delete_member_url');
        $this->p_search_member_url = config('payment.search_member_url');
        $this->p_update_member_url = config('payment.update_member_url');
        $this->p_search_trade_url = config('payment.search_trade_url');
    }

    //GMO会員登録
    public function registerMember($member_id)
    {
        Log::info("GMO会員登録: " . $member_id);
        $url = $this->p_save_member_url;
        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'MemberID' => $member_id
        ];
        $result = $this->curlData($url, $data);
        Log::info("GMO会員登録: " .  print_r($result, true));
        return $result;
    }

    //GMO会員参照
    public function searchMember($member_id)
    {
        Log::info("GMO会員参照: " . $member_id);
        $url = $this->p_search_member_url;

        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'MemberID' => $member_id
        ];

        $result = $this->curlData($url, $data);
        Log::info("GMO会員参照: " .  print_r($result, true));
        return $result;
    }

    //カード登録／更新
    public function registerCard($token, $member_id)
    {
        Log::info("GMOカード登録: " . $member_id);
        $url = $this->p_save_card_url;
        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'Token' => $token,
            'DefaultFlag' => 1,
            'MemberID' => $member_id
        ];

        $result = $this->curlData($url, $data);
        Log::info("GMOカード登録: " .  print_r($result, true));
        return $result;
    }

    //カード更新
    public function updateCard($token, $member_id, $card_seq)
    {
        Log::info("GMOカード変更: " . $member_id);
        $url = $this->p_save_card_url;
        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'Token' => $token,
            'MemberID' => $member_id,
            'DefaultFlag' => 1,
            'CardSeq' => $card_seq
        ];

        $result = $this->curlData($url, $data);
        Log::info("GMOカード変更: " .  print_r($result, true));
        return $result;
    }

    //カード削除
    public function deleteCard($member_id)
    {

        $url = $this->p_delete_card_url;
        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'MemberID' => $member_id,
            'CardSeq' => 0
        ];

        return $this->curlData($url, $data);

    }

    public function entry($order_id, $amount)
    {
        $url = $this->p_entry_tran_url;

        $data = [
            'ShopID' => $this->p_shop_id,
            'ShopPass' => $this->p_shop_pass,
            'OrderID' => $order_id,
            'JobCd' => 'CAPTURE',
            'Amount' => $amount,
        ];

        return $this->curlData($url, $data);

    }

    public function searchCard($member_id)
    {
        $url = $this->p_search_card_url;

        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'MemberID' => $member_id,
            'SeqMode' => 0
        ];

        return $this->curlData($url, $data);

    }

    public function execByMemberID($access_id, $access_pass, $order_id, $member_id, $card_seq)
    {
        $url = $this->p_exec_tran_url;

        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'AccessID' => $access_id,
            'AccessPass' => $access_pass,
            'OrderID' => $order_id,
            'Method' => 1,
            'MemberID' => $member_id,
            'CardSeq' => $card_seq,
        ];

        return $this->curlData($url, $data);

    }

    public function execByToken($access_id, $access_pass, $order_id, $token)
    {
        $url = $this->p_exec_tran_url;

        $data = [
            'SiteID' => $this->p_site_id,
            'SitePass' => $this->p_site_pass,
            'AccessID' => $access_id,
            'AccessPass' => $access_pass,
            'OrderID' => $order_id,
            'Method' => 1,
            'Token' => $token,
        ];

        return $this->curlData($url, $data);

    }


    public function getMemberByUser (User $obj_user) {
        if(!isset($obj_user->gmo_member_id)) {
            $gmo_user = $this->searchMember($obj_user->member_no);
            if(!isset($gmo_user['MemberID'])) {
                $gmo_user = $this->registerMember($obj_user->member_no);
            }

            return  isset($gmo_user['MemberID']) ? $gmo_user['MemberID'] : '';
        } else {
            return $obj_user->gmo_member_id;
        }
    }

    public function payForOrder(User $obj_user, $order_id, $amount, $token=null)
    {
        $has_error = false;
        $error_code = '';

        $result_entry = $this->entry($order_id, $amount);
        if ($result_entry['ErrInfo']) {
            return $result_entry;
        }

        $access_id = $result_entry['AccessID'];
        $access_pass = $result_entry['AccessPass'];

        if(is_null($token)) {
            $result_exec = $this->execByMemberID($access_id, $access_pass, $order_id, $obj_user->gmo_member_id, $obj_user->gmo_card_info->CardSeq);
        } else {
            $result_exec = $this->execByToken($access_id, $access_pass, $order_id,$token);
        }

        if ($result_exec['ErrInfo']) {
            $error_code = $result_exec['ErrInfo'];
            $has_error = true;
        }

        if($has_error) {
            $result['error_msg'] = '決済処理中にエラーが発生しました。' . ($error_code ? ("(" . $error_code . ")") : '');
            return $result;
        }
        return $result_exec;

    }

    public function curlData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));

        if (!$result = curl_exec($ch)) {
            echo curl_error($ch);
        }

        curl_close($ch);
        parse_str($result, $array);

        return $array;
    }

    public function getErrMsg($err_code)
    {
        if ( config('payment.gmo_error_msgs.' . $err_code)) {
            $result = config('payment.gmo_error_msgs.' . $err_code);
        } else {
            $result = '該当エラーコードがありません。';
        }
        return $result;
    }

    /*public function __construct()
    {
        $this->p_setting = new Setting();
        $this->p_setting->SetServerKey(config('const.v_air_direct_server_key'));
    }

    public function charge($order, $card_data) {
        $input = new ChargesParameter();
        $input->order_id = $order->order_no;
        $input->token_id = $card_data['card_token'];
        if(isset($card_data['save_card']) && $card_data['save_card']) {
            $input->register = true;
            $input->customer_id = true;
        }

        $input->gross_amount = $order->order_price;
        if(config('app.debug')) {
            $input->test_mode = true;
        }

        $charges = new Charges($this->p_setting);
        $result = false;

        try {
            $response = $charges->ChargeWithToken($input);
            $order->payment_log = $response;
            if($response['status'] == 'success') {
                $order->paid_at = Carbon::now()->format('Y-m-d H:i:s');
                $order->order_status = config('const.order_status_code.ordered');
                $result = true;
            } else {
                $order->order_status = config('const.order_status_code.pay_failed');
            }
        } catch (VtDirectException $ex) {
            Session::put('error',$ex->getMessage());
            $order->order_status = config('const.order_status_code.pay_failed');
        }
        $order->save();
        return $result;
    }*/


}
