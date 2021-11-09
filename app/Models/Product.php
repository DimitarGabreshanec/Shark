<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'type',
        'restaurant_kind',
        'restaurant_deposit',
        'product_no',
        'product_name',
        'list_reason',
        'available_from',
        'available_to',
        'price',
        'ship_price',
        'discount_type',
        'discount',
        'post_from',
        'post_to',
        'introduction',
        'quantity',
        'note',
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
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function obj_store(){
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function obj_main_img() {
        return $this->hasOne(ProductImg::class, 'product_id')->where('kind', config('const.image_kind_code.main'));
    }

    public function obj_sub_img() {
        return $this->hasMany(ProductImg::class, 'product_id')->where('kind', config('const.image_kind_code.sub'));
    }

    public function getRealPrice() {
        $real_price = $this->price;
        if($this->discount_type == config('const.discount_type.percent')){
            $real_price = (int)($real_price - $this->price * $this->discount / 100);
        } else {
            $real_price = (int)($this->price - $this->discount);
        }

        return $real_price;
    }

}
