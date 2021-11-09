<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'sequence',
        'layer',
    ];

    public static function boot() {
        parent::boot();

        static::deleting(function($vehicle) { // before delete() method call this
            $vehicle->obj_r_stores()->delete();
        });
    }

    public function obj_r_stores() {
        return $this->hasMany(StoreCategory::class, 'category_id');
    }


}
