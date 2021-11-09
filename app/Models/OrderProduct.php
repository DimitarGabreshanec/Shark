<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'cart_id',
        'user_id',
        'product_id',
        'store_id',
        'quantity',
        'price',                    //商品単価
        'discount_type',
        'discount',
        'discounted_price',         //割引価格
        'tax_price',                //税料
        'total_price',              //総価格
        'products_price',           //商品本価格
        'status',
        'completed_at',
        'note',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function obj_user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }

    public function obj_order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id')->withTrashed();
    }

    public function obj_cart()
    {
        return $this->belongsTo('App\Models\Cart', 'cart_id')->withTrashed();
    }

    public function obj_product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id')->withTrashed();
    }

    public function obj_store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id')->withTrashed();
    }
}
