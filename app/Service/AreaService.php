<?php

namespace App\Service;

use App\Models\Prefecture;
use Auth;
use DB;

class AreaService
{
    public static function getAllPrefecture() {
        return Prefecture::orderBy('id', 'asc')
            ->pluck('prefecture_name', 'id')
            ->all();
    }
 
    public static function getPrefectureNameByID($id) { 
        $obj_prefecture = Prefecture::find($id); 
        if(is_object($obj_prefecture)) {  
            return $obj_prefecture->prefecture_name;
        } else {
            return null;
        }

    }

}
