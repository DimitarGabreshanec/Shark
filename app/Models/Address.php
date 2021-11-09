<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'address';

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'post_first',
        'post_second',
        'prefecture',
        'address1',
        'address2',
        'address3',
        'tel',
        'note',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];


}
