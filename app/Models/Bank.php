<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_code',
        'branch_code',
        'name_kana',
        'name',
        'type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

}
