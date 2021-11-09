<?php

namespace App\Models;

use App\Notifications\Store\StoreCreateNotification;
use App\Notifications\Store\StoreMailChangedNotification;
use App\Notifications\Store\StorePasswordChangedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Store\ResetPasswordNotification;
use App\Notifications\Store\VerifyEmailCustom;

class Store extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_no',
        'email',
        'password',
        'type',
        'store_name',
        'post_first',
        'post_second',
        'prefecture',
        'store_address',
        'address_access',
        'tel',
        'charger_name',
        'work_from',
        'work_to',
        'url',
        'detail',
        'bank',
        'bank_branch',
        'account_type',
        'account_no',
        'account_name',
        'status',
        'position',
        'last_login_at',
        'email_verified_at',
        'email_verify_token',
        'note',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verify_token',
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
        'position' => 'array',

    ];
    /**
     * @var mixed
     */

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    public function sendStoreCreateNotification()
    {
        $this->notify(new StoreCreateNotification($this['email'], '12345678'));
    }

    public function sendStoreMailUpdatedNotification($old_email, $email)
    {
        $this->notify(new StoreMailChangedNotification($old_email, $email));
    }

    public function sendStorePasswordUpdatedNotification($password)
    {
        $this->notify(new StorePasswordChangedNotification($this['email'], $password));
    }

    public function getPositionAttribute($value)
    {
        return json_decode($value);
    }

    public function obj_categories() {
        return $this->belongsToMany(Category::class, 'store_categories', 'store_id', 'category_id');
    }

    public function obj_r_categories() {
        return $this->hasMany(StoreCategory::class, 'store_id');
    }

    public function getRangePrice($type) {
        $price = $this->obj_products->where('type', $type);
        return [
            'min' =>  $price->min('price'),
            'max' => $price->max('price')
        ];
    }

    public function getProductArray($type) {
        return $this->obj_products->where('type', $type);
    }

    public function getProductForId($chekck_products, $type) {
        $query = '';
        $product = $this->obj_products->where('type', $type);
        foreach($chekck_products as $key => $on){
            if($on == 'on'){
                $product = $product->where(function($query) use($key){
                    $query->orWhere('id', '=', $key);
                });
            }
        }
        return $product;
    }

    public function obj_prefecture() {
        return $this->belongsTo(Prefecture::class, 'prefecture');
    }

    public function getPrefectureName() {
        return is_object($this->obj_prefecture) ? $this->obj_prefecture->prefecture_name : '';
    }

    public function obj_products() {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function getProductCountByType($type) {
        return $this->obj_products->where('type', $type)->count();
    }

    public function obj_main_img() {
        return $this->hasOne(StoreImg::class, 'store_id')->where('kind', config('const.image_kind_code.main'));
    }

    public function obj_sub_img() {
        return $this->hasMany(StoreImg::class, 'store_id')->where('kind', config('const.image_kind_code.sub'));
    }

    public function arr_categories() {
        return $this->obj_categories()->pluck('category_id')->all();
    }

    public function getPosition() {
        $position = $this['position'];
        $position = json_decode($position, true);
        $pos['lat'] = isset($position['lat']) ? $position['lat'] : null;
        $pos['lng'] = isset($position['lng']) ? $position['lng'] : null;
        return $pos;
    }
}
