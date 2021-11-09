<?php

namespace App\Service;

use App\Models\Configuration;
use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Hash;

use Auth;
use DB;

class ConfigurationService
{
    public static function doUpdate(Configuration $configuration, $data) {

        if ($configuration->update($data) ) {
            return $configuration;
        } else {
            return null;
        }
    }

    public static function getTaxPrice($price) {
        $configuration = Configuration::orderByDesc('id')->first();
        return $price * $configuration->tax_rate / 100;
        
    }

    public static function getTaxRate() {
        $configuration = Configuration::orderByDesc('id')->first();
        return $configuration->tax_rate / 100;
    }

    public static function getFeeFix() {
        $configuration = Configuration::orderByDesc('id')->first();
        return $configuration->fee_fix;
    }

    public static function getFeePercent() {
        $configuration = Configuration::orderByDesc('id')->first();
        return $configuration->fee_percent;
    }

    public static function getStoreFee($price) {
        $configuration = Configuration::orderByDesc('id')->first();
        return $configuration->fee_fix + $price * $configuration->fee_percent / 100;
    }

}
