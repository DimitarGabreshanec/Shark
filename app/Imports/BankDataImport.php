<?php

namespace App\Imports;

use App\Models\Bank;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use DB;

class BankDataImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $params = [
            'bank_code' => trim($row[0]),
            'branch_code' => trim($row[1]),
            'name_kana' => trim($row[2]),
            'name' => trim($row[3]),
            'type' => trim($row[4]),
        ];

        Bank::create($params);
    }

}
