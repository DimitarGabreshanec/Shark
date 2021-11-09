<?php

namespace App\Service;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Prefecture;
use App\Models\StoreImg ;
use Carbon\Carbon;
use App\Models\Store;
use Storage;
use Auth;
use DB;
use Hash;

use function GuzzleHttp\Promise\all;

class StoreService {

    public static function doSearch($search_params)
    {
        $stores = Store::orderByDesc('updated_at');

        if(isset($search_params['store_no']) && !empty($search_params['store_no'])) {
            $stores->where('store_no', 'like', '%' . $search_params['store_no'] . '%');
        }
        if(isset($search_params['email']) && !empty($search_params['email'])) {
            $stores->where('email', 'like', '%' . $search_params['email'] . '%');
        }
        if(isset($search_params['store_name']) && !empty($search_params['store_name'])) {
            $stores->where('store_name', 'like', '%' . $search_params['store_name'] . '%');
        }
        if(isset($search_params['status']) && $search_params['status'] != 'all') {
            $stores->where('status', $search_params['status']);
        }

        return $stores;
    }

    public static function updateImasugu($index_params) {
        $user = Auth::user();
        if(isset($index_params['check'])){
            $user->update(['imasugu'=> self::formatImasugu($index_params)]);
        }
        return json_decode($user['imasugu'], true);
    }

    public static function formatImasugu($imasuguData) {
        $imasugu_ = [];
        $imasugu_['categories']= [];
        $imasugu_['check'] = $imasuguData['check'];
        if(isset($imasuguData['categories'])){
            foreach($imasuguData['categories'] as $key => $value) {
                array_push($imasugu_['categories'], $key);
            }
        }
        return json_encode($imasugu_);
    }

    public static function doGetImasuguList($search_params) {

        $search_params['type'] = config('const.product_type_code.fix');
        $stores = Store::orderByDesc('updated_at');
        $stores->whereHas('obj_products', function($query) use ($search_params){
            $query->where('type', $search_params['type']);
        });
        if(isset($search_params['categories']) && !empty($search_params['categories'])) {

            $obj_categories = Category::find($search_params['categories']);
            $arr_category = $search_params['categories'];
            $stores->whereHas('obj_r_categories', function($query) use ($arr_category){
                $query->whereIn('category_id', $arr_category);
            });
            return $stores->get();
        }
        return null;
    }

    public static function doGetECList($search_params) {

        $stores = Store::orderByDesc('updated_at');
        $stores->whereHas('obj_products', function($query) use ($search_params){
            if(isset($search_params['type']) && !empty($search_params['type'])) {
                $query->where('type', $search_params['type']);
            }
            if(isset($search_params['price_from']) && !empty($search_params['price_from'])) {
                $query->where('price', '>=', $search_params['price_from']);
            }
            if(isset($search_params['price_to']) && !empty($search_params['price_to'])) {
                $query->where('price', '<=', $search_params['price_to']);
            }
        });

        if(isset($search_params['category_id']) && !empty($search_params['category_id'])) {
            $arr_category[] = $search_params['category_id'];
            $obj_category = Category::find($search_params['category_id']);

            if($obj_category->parent_id == 0) {
                $arr_category = array_merge($arr_category, CategoryService::getSubCategoryIDs($search_params['category_id']));
            }

            $stores->whereHas('obj_r_categories', function($query) use ($arr_category){
                $query->whereIn('category_id', $arr_category);
            });
        }

        if(isset($search_params['prefecture']) && !empty($search_params['prefecture'])) {
            $stores->where('prefecture',$search_params['prefecture']);
        }

        return $stores->get();
    }

    public static function getFavoriteStores($product_type)
    {
        $stores = Auth::user()->obj_store_get_favorites($product_type );
        return $stores->get();
    }

    public static function doCreate($data) {
        $data['work_from'] = $data['work_from_hour'] ? Carbon::parse(str_pad($data['work_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['work_from_minute'], 2, "0", STR_PAD_LEFT))->format('H:i') : null;
        $data['work_to'] = $data['work_to_hour'] ? Carbon::parse(str_pad($data['work_to_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['work_to_minute'], 2, "0", STR_PAD_LEFT))->format('H:i') : null;

        $data['store_no'] = self::generateStoreCode();
        $data['password'] = Hash::make('12345678');
        $data['status'] = config('const.store_status_code.registered');

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_store = Store::create($data) ) {
            $obj_store->obj_categories()->sync($data['category']);
            if(isset($data['main_img'])) {
                self::doCreateOrUpdateMainImg($obj_store, $data['main_img']);
            }
            if(isset($data['sub_img'])) {
                self::doCreateOrUpdateSubImg($obj_store, $data['sub_img']);
            }

            $obj_store->sendStoreCreateNotification();

            return $obj_store;
        } else {
            return null;
        }
    }

    public static function doCreateFront($data) {

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_store = Store::create($data) ) {
            return $obj_store;
        } else {
            return null;
        }
    }

    public static function doUpdate(Store $obj_store, $data) {

        if(!empty($data['work_from_hour']) && !empty($data['work_from_hour'])) {
            $data['work_from'] = $data['work_from_hour'] ? Carbon::parse(str_pad($data['work_from_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['work_from_minute'], 2, "0", STR_PAD_LEFT))->format('H:i') : null;
        }
        if(!empty($data['work_to_hour']) && !empty($data['work_to_hour'])) {
            $data['work_to'] = $data['work_to_hour'] ? Carbon::parse(str_pad($data['work_to_hour'], 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['work_to_minute'], 2, "0", STR_PAD_LEFT))->format('H:i') : null;
        }

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_store->update($data) ) {
            $obj_store->obj_categories()->sync($data['category']);
            if(isset($data['main_img'])) {
                self::doCreateOrUpdateMainImg($obj_store, $data['main_img']);
            }
            if(isset($data['sub_img'])) {
                self::doCreateOrUpdateSubImg($obj_store, $data['sub_img']);
            }
            return $obj_store;
        } else {
            return null;
        }
    }

    public static function getJsonPositionData($params) {

        if (!empty($params['store_lat']) && !empty($params['store_lng'])) {
            $position['lat'] = $params['store_lat'];
            $position['lng'] = $params['store_lng'];
            return json_encode($position);
        } else {
            return null;
        }

    }

    public static function doCreateOrUpdateSubImg(Store $store, $sub_images) {
        $sequence = $store->obj_sub_img()->pluck('sequence');
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
                StoreImg::create([
                    'store_id'=>$store->id,
                    'kind'=>config('const.image_kind_code.sub'),
                    'img_name'=>$sub_image,
                    'sequence'=>$sequence,
                ]);
                if( Storage::disk('temp')->exists($sub_image)){
                    $sub_file = Storage::disk('temp')->get($sub_image);
                    Storage::disk('stores')->put($store->id . '/' . $sub_image, $sub_file);
                    Storage::disk('temp')->delete($sub_image);
                }
                $sequence++;
            }
        }
    }

    public static function doCreateOrUpdateMainImg(Store $store, $file_name) {
        $obj_main_img = $store->obj_main_img;
        if(is_object($obj_main_img)) {
            $store->obj_main_img()->update(['img_name'=>$file_name]);
            $old_file_name = $obj_main_img->img_name;
        } else {
            $store->obj_main_img()->create([
                'store_id'=>$store->id,
                'kind'=>config('const.image_kind_code.main'),
                'img_name'=>$file_name,
            ]);
        }

        if( Storage::disk('temp')->exists($file_name)){
            $main_file = Storage::disk('temp')->get($file_name);
            Storage::disk('stores')->put($store->id . '/' . $file_name, $main_file);
            Storage::disk('temp')->delete($file_name);

        }

    }

    public static function doUpdateFront(Store $obj_store, $data) {
        if(isset($data['password']) && !empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }

        $data['position'] = self::getJsonPositionData($data);

        if ($obj_store->update($data) ) {
            if(isset($data['main_img'])) {
                self::doCreateOrUpdateMainImg($obj_store, $data['main_img']);
            }
            return $obj_store;
        } else {
            return null;
        }
    }

    public static function doDelete($obj_store) {
        if($obj_store->delete()) {
            $obj_store->obj_categories()->detach();
            return true;
        } else {
            return false;
        }
    }

    public static function getAllStores()
    {
        $stores = Store::orderBy('store_name')
            ->where('status', config('const.store_status_code.registered'))
            ->select('store_name', 'id')
            ->pluck('store_name', 'id')
            ->all();
        return $stores;
    }

    public static function getStoreName($store_id)
    {
        $store_name = Store::orderBy('id')
            ->where('id', '=', $store_id)->first();  
        return isset($store_name) ? $store_name->store_name : null;
    }

    public static function generateStoreCode() {
        $latest_code = Store::withTrashed()->orderByDesc('store_no')->first();
        if(is_object($latest_code)) {
            $latest_code_no = (int)str_replace('S', '', ltrim($latest_code->store_no, '0'));
            $new_number = $latest_code_no + 1;
            $number_length = strlen($new_number) > 6 ?  strlen($new_number)  : 6;
            $new_partner_code = 'S'.str_pad($new_number, $number_length, "0", STR_PAD_LEFT) ;
        } else {
            $new_partner_code = 'S000001';
        }

        return $new_partner_code;
    }

    public static function mile_distance($lat1, $lng1, $lat2, $lng2) {
        $R = 3958.8; // Radius of the Earth in miles
        $rlat1 = $lat1 * (3.141592654/180); // Convert degrees to radians
        $rlat2 = $lat2 * (3.141592654/180); // Convert degrees to radians
        $difflat = $rlat2-$rlat1; // Radian difference (latitudes)
        $difflon = ($lng2-$lng1) * (3.141592654/180); // Radian difference (longitudes)
        $d = 2 * $R * asin(sqrt(sin($difflat/2)*sin($difflat/2)+cos($rlat1)*cos($rlat2)*sin($difflon/2)*sin($difflon/2)));
        return $d;
    }

    public static function getImasugu(){
        $user = Auth::user();
        return json_decode($user['imasugu'], true);
    }

    public static function getAllImasuguStores()
    {
        $imasugu = self::getImasugu();
        $search_params['check'] = isset($imasugu['check']) ? $imasugu['check'] : 0;
        $search_params['categories'] = isset($imasugu['categories']) ? $imasugu['categories'] : null;
        $stores = self::doGetImasuguList($search_params);
        //add-----------------------------------------------------------
        $new_store=[];
        if(isset($stores)){
            $user = Auth::user();
            $self_pos = $user->getPosition();
            $my_lat = isset($self_pos['lat']) ? $self_pos['lat'] : null;
            $my_lng = isset($self_pos['lng']) ? $self_pos['lng'] : null; 
            foreach($stores as $store){
                $pos = $store->getPosition();
                $lat = isset($pos['lat']) ? $pos['lat'] : null;
                $lng = isset($pos['lng']) ? $pos['lng'] : null; 
                $distance = self::mile_distance($my_lat, $my_lng, $lat, $lng); 
                if($distance < 5){
                    array_push($new_store, $store);
                }
            } 
        }
        
        //--------------------------------------------------------------
        return $new_store;
    }

    public static function getImasuguStores($lat, $lng)
    {
        $stores = Store::orderBy('id')
                    ->where('status', '=', config('const.store_status_code.registered'))
                    ->get(); 
        $new_store=[];
        foreach($stores as $store){
            $self_pos = $store->getPosition();
            $my_lat = isset($self_pos['lat']) ? $self_pos['lat'] : null;
            $my_lng = isset($self_pos['lng']) ? $self_pos['lng'] : null; 
            $distance = self::mile_distance($my_lat, $my_lng, $lat, $lng); 
            if($distance < 5){
                array_push($new_store, $store);
            }
        } 
        return $new_store;
    }

    public static function getImasuguCategories(){
        $imasugu = self::getImasugu();
        $categories = isset($imasugu['categories']) ? $imasugu['categories'] : null;
        return $categories;
    }

    public static function doUpdateBank(Store $store, $data){
        $store->update($data);
        return $store;
    }


}
