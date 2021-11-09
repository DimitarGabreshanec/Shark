<?php

namespace App\Models;

use App\Notifications\User\ResetPasswordNotification;
use App\Notifications\User\UserCreateNotification;
use App\Notifications\User\UserMailChangedNotification;
use App\Notifications\User\UserPasswordChangedNotification;
use App\Notifications\User\VerifyEmailCustom;
use App\Service\CartService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_no',
        'name',
        'email',
        'birthday',
        'password',
        'address',
        'post_first',
        'post_second',
        'prefecture',
        'email_verified_at',
        'email_verify_token',
        'gender',
        'status',
        'social',
        'api_token',
        'firebase_token',
        'gmo_member_id',
        'gmo_card_info',
        'imasugu',
        'position',
        'last_login_at'
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
        'social' => 'array',
        'gmo_card_info' => 'array',
        'imasugu' => 'array',
        'position' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getGmoCardInfoAttribute($value)
    {
        return json_decode($value);
    }

    public function getSocialAttribute($value)
    {
        return json_decode($value);
    }

    public function getImasuguAttribute($value)
    {
        return json_decode($value);
    }

    public function getPositionAttribute($value)
    {
        return json_decode($value);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom());
    }

    public function sendUserCreateNotification()
    {
        $this->notify(new UserCreateNotification($this['email'], '12345678'));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendMailUpdateNotification($old_email, $email)
    {
        $this->notify(new UserMailChangedNotification($old_email, $email));
    }


    public function sendPasswordUpdateNotification($password)
    {
        $this->notify(new UserPasswordChangedNotification($this['email'], $password));
    }


    public function obj_store_favorites() {

        return $this->belongsToMany(Store::class, 'favorites', 'user_id', 'store_id');
    }

    public function obj_store_get_favorites($product_type) {

        return $this->belongsToMany(Store::class, 'favorites', 'user_id', 'store_id')->where('product_type', $product_type);
    }

    public function arr_favorite_stores($product_type) {

        return $this->obj_store_get_favorites($product_type)->pluck('store_id')->all();
    }

    public function opened_cart() {
        return $this->hasOne(Cart::class,'user_id')->where('cart_status', config('const.cart_status_code.active'));
    }

    public function getPosition() {
        $position = json_decode($this->position, true);
        $pos['lat'] = isset($position['lat']) ? $position['lat'] : null;
        $pos['lng'] = isset($position['lng']) ? $position['lng'] : null;
        return $pos;
    }

    public function getUserCartProductCount() {
        if(is_object($this->opened_cart)) {
            return $this->opened_cart->getProductCount();
        }
        return 0;
    }

    public function getUserCartProducts() {
        if(is_object($this->opened_cart)) {
            return $this->opened_cart->cart_products;
        }
        return null;
    }

}
