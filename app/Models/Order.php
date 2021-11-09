<?php

namespace App\Models;

use App\Service\AreaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_no',
        //'stripe_order_no',
        'order_type',
        'order_status',
        'cart_id',
        'user_id',
        'products_price',   //商品本価格
        'discounted_price',   //割引価格
        'tax_price',         //税料
        'ship_price',        //送料
        //'cart_price',
        'order_price',
        'ordered_at',
        'paid_at',
        'completed_at',
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
        'order_note',
        'payment_log',
        'note',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];


    public function getPaymentLogAttribute($value)
    {
        return json_decode($value);
    }

    public function obj_user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function obj_cart()
    {
        return $this->hasOne('App\Models\Cart', 'id', 'cart_id');
    }

    public function obj_order_products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id');
    }

    public function arr_order_products()
    {
        $result = [];
        foreach ($this->obj_order_products as $order_product) {
            $product['id'] = $order_product->obj_product->id;
            $product['name'] = $order_product->obj_product->product_name;
            $product['quantity'] = $order_product->quantity;
            array_push($result, $product);
        }
        return $result;
    }

    public function target_client()
    {
        return $this->last_name . '　'. $this->first_name;
    }

    public function target_client_kana()
    {
        return $this->last_name_kana . '　'. $this->first_name_kana;
    }

    public function target_zip()
    {
        return $this->post_first . '-'. $this->post_second;
    }

    public function target_address()
    {
        return AreaService::getPrefectureNameByID($this->prefecture).$this->address1.$this->address2.$this->address3;
    }

}
