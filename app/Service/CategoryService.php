<?php

namespace App\Service;

use App\Models\Category;
use Carbon\Carbon;
use App\Models\Store;
use Storage;
use Auth;
use DB;

class CategoryService {

    static $categories=[]; 

    public static function doCreate($data){ 
        return Category::create($data);
    }

    public static function doUpdate($data){
        
        $sub_objs = Category::where('id', $data['id'])->first();  
        if(is_object($sub_objs)){
            return $sub_objs->update($data);
        }
        return null;
    }

    public static function doSequenceUpdate($data){    
        foreach($data['sequence'] as $key => $value){  
            $category_obj = Category::where('id', $key)->first(); 
            if(is_object($category_obj)){
                $param['sequence'] = $value;
                $category_obj->update($param); 
            } 
        } 
        return true;
    }
    

    public static function doDelete($obj_category){
        if ($obj_category->delete()) {
            $sub_objs = Category::where('parent_id', $obj_category->id); 
            foreach($sub_objs as $sub_obj){
                $sub_obj->delete();
            }
            return true;
        } else {
            return false;
        } 
    }

    public static function getParentCategories() {
        return Category::where('parent_id', 0)
            ->orderBy('sequence')
            ->select('name', 'id' ,'sequence')
            ->get()
            ->toArray();

    }

    public static function getParentCategoryName($category_id) {
        return Category::where('id', $category_id) 
            ->orderBy('sequence')
            ->select('name', 'id')
            ->get();

    }

    public static function getCategoryName($category_id) {
        return Category::where('id', $category_id) 
            ->orderBy('sequence')
            ->pluck('name');
    }

    public static function getCategoryParentId($category_id) {
        return Category::where('id', $category_id) 
            ->orderBy('sequence')
            ->select('name', 'parent_id')
            ->get();

    }

    public static function getSubCategories($parent_id) {
        return Category::where('parent_id', $parent_id)
            ->orderBy('sequence')
            ->select('name', 'id', 'sequence', 'parent_id', 'layer')
            ->get()
            ->toArray();
    }
 /*
    static $fullCategoryName = "";
    public static function getFullCategoryName($child)
    {
        self::$fullCategoryName = "";  
        self::_getFullCategoryName($child);  
        return self::$fullCategoryName;

    }

    private static function _getFullCategoryName($child)
    {    
        if(isset($child['parent_id'])){
            self::$fullCategoryName = $child['name'] . ' ' . self::$fullCategoryName;
            $child = self::getCategoryParentId($child['parent_id']);    
            self::_getFullCategoryName($child);  
        } 
    }
*/
    public static function getAllCategories(){
        self::$categories=[];
        self::getCategory();
        return self::$categories;

    }

    public static function getCategory($parent_id = 0){
        $childs = self::getSubCategories($parent_id);
        if(sizeof($childs) > 0){ 
            foreach($childs as $child){
                //parent category process
                $child['has_child'] = false;
                if(self::getSubCategories($child['id'])){
                    $child['has_child'] = true;
                }  
                array_push(self::$categories, $child);
                $parent_id = $child['id'];
                self::getCategory($parent_id);
            }
        }  
    }

    public static function getSubCategoryIDs($parent_id) {
        return Category::where('parent_id', $parent_id)
            ->orderBy('sequence')
            ->pluck('id')
            ->all();
    }

    public static function getTreeData() {

    }

}
