<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StoreCategory extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'category_id',
    ];

    public function obj_store(){
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function obj_category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

}
