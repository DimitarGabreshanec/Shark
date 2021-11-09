<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    public $table = 'prefectures';

    protected $fillable = [
        'region_id',
        'prefecture_name',
    ];

    public $primaryKey = 'id';

}
