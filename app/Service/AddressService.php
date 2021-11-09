<?php

namespace App\Service;

use App\Models\Address; 

class AddressService
{
    public static function doCreate($data) {
        $address = Address::orderByDesc('user_id')->where('user_id', '=', $data['user_id'])->first();   
        if(isset($address) && !empty($address)){ 
            $address->update($data);
            return $address;
        } 
        if ($address = Address::create($data)) {
            return $address;
        }
        return null;
    } 

    public static function doSearch($search_params){

        $address = Address::orderByDesc('user_id');  
        if(isset($search_params['user_id']) && !empty($search_params['user_id'])) {
            $address = $address->where('user_id', '=', $search_params['user_id']);
        }  
        return $address->first();
    }
  

}