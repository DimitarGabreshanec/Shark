<?php

namespace App\Service;

use App\Models\Store;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductImg;
use Storage;
use Auth;
use DB;
use Hash;

class ProductService {

    public static function doSearch($search_params)
    { 
        $product = Product::orderByDesc('updated_at');

        if(isset($search_params['product_name']) && !empty($search_params['product_name'])) {
            $product->where('product_name', 'like', '%' . $search_params['product_name'] . '%');
        }
        if(isset($search_params['price_from']) && !empty($search_params['price_from'])) {
            $product->where('price', '>=', $search_params['price_from']);
        }
        if(isset($search_params['price_to']) && !empty($search_params['price_to'])) {
            $product->where('price', '<=', $search_params['price_to']);
        }
        if(isset($search_params['store_name']) && !empty($search_params['store_name'])) {
            $product->whereHas('obj_store', function($query) use ($search_params) {
                $query->where('store_name', 'like', '%' . $search_params['store_name'] . '%');
            });
        }
        if(isset($search_params['type']) && $search_params['type'] != 'all') {
            $product->where('type', $search_params['type']);
        }
        if(isset($search_params['id']) && empty($search_params['id'])) {
            $product->where('id', $search_params['id']);
        }
        return $product;
    }

    public static function doSearchProductInStore($search_params)
    {
        $product = Product::orderByDesc('updated_at');
        if(isset($search_params['store_id']) && !empty($search_params['store_id'])) {
            $store_id = $search_params['store_id'];
            $product->whereHas('obj_store', function($query) use ($store_id) {
                $query->where('store_id', '=',  $store_id);
            });
        }
        if(isset($search_params['type']) && $search_params['type'] != 'all') {
            $product->where('type', $search_params['type']);

        }  
        return $product->get();
    }  

    public static function doCreate($data) { 

        if(isset($data['available_from_hour'])){
            $data['available_from'] = $data['available_from_hour'] ? Carbon::parse(str_pad($data['available_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['available_from_minute'], 2, "0", STR_PAD_LEFT))->format('H:i') : null;
        }
        if(isset($data['available_to_hour'])){
            $data['available_to'] = $data['available_to_hour'] ? Carbon::parse(str_pad($data['available_to_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['available_to_minute'], 2, "0", STR_PAD_LEFT))->format('H:i'): null;$data['post_from'] = $data['post_from_date'] ? Carbon::parse($data['post_from_date'] . ' ' . str_pad($data['post_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['post_from_minute'], 2, "0", STR_PAD_LEFT))->format('Y-m-d H:i') : null;
        }
        if(isset($data['post_from_date'])){
            $data['post_from'] = $data['post_from_date'] ? Carbon::parse($data['post_from_date'] . ' ' . str_pad($data['post_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['post_from_minute'], 2, "0", STR_PAD_LEFT))->format('Y-m-d H:i') : null;
        }
        if(isset($data['post_to_date'])){
            $data['post_to'] = $data['post_to_date'] ? Carbon::parse($data['post_to_date'] . ' ' . str_pad($data['post_to_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['post_to_minute'], 2, "0", STR_PAD_LEFT))->format('Y-m-d H:i') : null;
        }
        if(isset($data['type'])){
            $data['product_no'] = self::generateProductCode($data['type']);
        }  

        if ($obj_product = Product::create($data) ) {
            if(isset($data['main_img'])) {
                self::doCreateOrUpdateMainImg($obj_product, $data['main_img']);
            }
            if(isset($data['sub_img'])) {
                self::doCreateOrUpdateSubImg($obj_product, $data['sub_img']);
            }
            return $obj_product;
        } else {
            return null;
        }
    }

    public static function doCreateOrUpdateMainImg(Product $product, $file_name) {
        $obj_main_img = $product->obj_main_img;
        if(is_object($obj_main_img)) {
            $product->obj_main_img()->update(['img_name'=>$file_name]);
            $old_file_name = $obj_main_img->img_name;
        } else {
            $product->obj_main_img()->create([
                'product_id'=>$product->id,
                'kind'=>config('const.image_kind_code.main'),
                'img_name'=>$file_name,
            ]);
        }

        if( Storage::disk('temp')->exists($file_name)){
            $main_file = Storage::disk('temp')->get($file_name);
            Storage::disk('products')->put($product->id . '/' . $file_name, $main_file);
            Storage::disk('temp')->delete($file_name);
            if(isset($old_file_name)) {
                Storage::disk('products')->delete($product->id . '/' . $old_file_name);
            }
        }

    }

    public static function doCreateOrUpdateSubImg(Product $product, $sub_images) { 
        $sequence = $product->obj_sub_img()->pluck('sequence');
        //how to get max value, for example {0:1, 1:4, 2: 5, 3:6}
        $max = 0;
        for($i=0; $i<sizeof($sequence); $i++){
            if($max < $sequence[$i]){
                $max = $sequence[$i];
            }
        } 
        $sequence = $max + 1;
        foreach($sub_images as $sub_image){  
            if(isset($sub_image)){
                ProductImg::create([
                    'product_id'=>$product->id,
                    'kind'=>config('const.image_kind_code.sub'),
                    'img_name'=>$sub_image,
                    'sequence'=>$sequence,
                ]); 
                if( Storage::disk('temp')->exists($sub_image)){
                    $sub_file = Storage::disk('temp')->get($sub_image);
                    Storage::disk('products')->put($product->id . '/' . $sub_image, $sub_file);
                    Storage::disk('temp')->delete($sub_image); 
                }
                $sequence++;
            } 
        } 
    }

    public static function doCreateFront($data) {
        $product = new Product();
        if ($product->create($data) ) {
            return $product;
        } else {
            return null;
        }
    }

    public static function doUpdate(Product $obj_product, $data) {
 
        if(isset($data['available_from_hour'])){
            $data['available_from'] = $data['available_from_hour'] ? Carbon::parse(str_pad($data['available_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['available_from_minute'], 2, "0", STR_PAD_LEFT))->format('H:i') : null;
        }
        if(isset($data['available_to_hour'])){
            $data['available_to'] = $data['available_to_hour'] ? Carbon::parse(str_pad($data['available_to_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['available_to_minute'], 2, "0", STR_PAD_LEFT))->format('H:i'): null;$data['post_from'] = $data['post_from_date'] ? Carbon::parse($data['post_from_date'] . ' ' . str_pad($data['post_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['post_from_minute'], 2, "0", STR_PAD_LEFT))->format('Y-m-d H:i') : null;
        }
        if(isset($data['post_from_date'])){
            $data['post_from'] = $data['post_from_date'] ? Carbon::parse($data['post_from_date'] . ' ' . str_pad($data['post_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['post_from_minute'], 2, "0", STR_PAD_LEFT))->format('Y-m-d H:i') : null;
        }
        if(isset($data['post_to_date'])){
            $data['post_to'] = $data['post_to_date'] ? Carbon::parse($data['post_to_date'] . ' ' . str_pad($data['post_to_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['post_to_minute'], 2, "0", STR_PAD_LEFT))->format('Y-m-d H:i') : null;
        }
        if(isset($data['type'])){
            $data['product_no'] = self::generateProductCode($data['type']);
        }
        if(isset($data['restaurant_kind']) && $data['restaurant_kind'] != config('const.restaurant_kind_code.seat')){
            $data['restaurant_deposit'] = 0;
        }

        if ($obj_product->update($data) ) {
            if(isset($data['main_img'])) {
                self::doCreateOrUpdateMainImg($obj_product, $data['main_img']);
            }

            if(isset($data['sub_img'])) {
                self::doCreateOrUpdateSubImg($obj_product, $data['sub_img']);
            }
            return $obj_product;

            return $obj_product;
        } else {
            return null;
        }
    }



    public static function doUpdateFront(Product $product, $data) {

        if ($product->update($data) ) {
            return $product;
        } else {
            return null;
        }
    }

    public static function doDelete($product) {
        if($product->delete()) {
            return true;
        } else {
            return false;
        }
    }

    //店頭商品 PS0000001
    //通販商品 PE0000001
    public static function generateProductCode($type) {
        $prefix_code = $type==config('const.product_type_code.fix') ? 'PS' :'PE' ;
        $latest_code = Product::orderByDesc('product_no')
            ->where('product_no', 'like', "{$prefix_code}%")
            ->first();
        if(is_object($latest_code)) {
            $latest_code_no = (int)str_replace($prefix_code, '', ltrim($latest_code->product_no, '0'));
            $new_number = $latest_code_no + 1;
            $number_length = strlen($new_number) > 7 ?  strlen($new_number)  : 7;
            $new_product_code = $prefix_code . str_pad($new_number, $number_length, "0", STR_PAD_LEFT) ;
        } else {
            $new_product_code = $prefix_code . '0000001';
        }

        return $new_product_code;
    }

}
