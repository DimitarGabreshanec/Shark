<?php

namespace App\Service;

use App\Models\User;
use Carbon\Carbon;
use Storage;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;

use Auth;
use DB;

class VeritransAirPaymentService
{
    public static function getPaymentData($order_data)
    {
        foreach ($order_data as $store_data) {
            if(isset($store_data['products']) && count($store_data['products']) > 0) {

            }
        }
    }
}
