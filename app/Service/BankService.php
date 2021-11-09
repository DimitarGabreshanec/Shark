<?php

namespace App\Service;

use App\Models\Bank;
use Auth;
use DB;

class BankService
{
    public static function getBanks() {
        return Bank::orderBy('bank_code', 'asc')
            ->where('type', 1)  //bank type
            ->select('id', 'name', 'bank_code')
            ->get()->toArray();
    }

    public static function getBranch($bank_code) {
        return Bank::orderBy('branch_code', 'asc')
            ->where('type', 2)  //branch type
            ->where('bank_code', $bank_code)  //branch type
            ->select('id', 'name', 'branch_code')
            ->get()->toArray();
    }

}
