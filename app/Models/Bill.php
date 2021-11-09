<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Bill extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'year',
        'month',
        'status',
        'fix_price',
        'ec_price',
        'fix_fee',
        'ec_fee',
        'total_price',
        'fix_order_products',
        'ec_order_products',
        'fee_fix',
        'fee_percent',
        'applied_at',
        'completed_at',
        'comment', 
        'created_by', 
        'updated_by', 
        'deleted_by', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [ 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ]; 

}
