<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Cart extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'products_price',   //商品本価格
        'discounted_price', //割引価格
        'tax_price',        //税料
        'ship_price',        //送料
        'cart_price',       //カート価格
        'cart_status',
        'note',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }

    public function cart_products()
    {
        return $this->hasMany('App\Models\CartProduct', 'cart_id');
    }

    public function getProductCount()
    {
        return DB::table('cart_products')
            ->where('cart_id', $this->id)
            ->where('user_id', $this->user_id)
            ->sum('quantity');
    }

}
