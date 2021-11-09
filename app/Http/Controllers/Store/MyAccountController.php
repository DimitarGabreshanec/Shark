<?php

namespace App\Http\Controllers\Store;
use Session;
use App\Service\StoreService;
use Illuminate\Http\Request;
use App\Models\Store;
use Auth;
use App\Http\Requests\Store\StoreRequest;
use App\Models\StoreImg;
use DateTime;
use Storage;
use Illuminate\Support\Carbon;

use function GuzzleHttp\Promise\all; 

class MyAccountController extends StoreController
{
    public function edit(Request $request)
    {  
        $this->f_menu = config('const.footer_store_menu_kind_en.shop'); 
        $search_params = $request->all();   
        $store = $this->getAuthStore(); 
        return view("{$this->platform}.my_account.edit", [
            'store' => $store,
        ]);
    }

    public function info()
    {   
        $this->f_menu = config('const.footer_store_menu_kind_en.shop'); 
        $store = $this->getAuthStore(); 
        return view("store.my_account.info", [
            'store' => $store, 
        ]);
    } 

    public function update(StoreRequest $request)
    {  
        $this->f_menu = config('const.footer_store_menu_kind_en.shop');  
        $search_param = $request->all(); 
        $uploaded_file = $request->file('main_img');   
        if($uploaded_file != null){
            $now = DateTime::createFromFormat('U.u', microtime(true)); 
            $new_filename = "main_".$now->format("YmdHisu"). '.'. $uploaded_file->getClientOriginalExtension(); 
            $uploaded_file->storeAs('public/temp', $new_filename); 
            $search_param['main_img'] = $new_filename;
        }
          
        $uploaded_sub_files = $request->file('sub_img');    
        $sub_img=[]; 
        if(isset($uploaded_sub_files)){ 
            foreach($uploaded_sub_files as $file){  
                if(!empty($file)){ 
                    $now = DateTime::createFromFormat('U.u', microtime(true)); 
                    $new_filename = "sub_".$now->format("YmdHisu"). '.'. $file->getClientOriginalExtension();  
                    $file->storeAs('public/temp', $new_filename); 
                    array_push($sub_img, $new_filename);
                }
            }  
            $search_param['sub_img'] = $sub_img;  
        } 
       

        $store = $this->getAuthStore();  
        $search_param['store_id'] = $store->id;

        if (StoreService::doUpdate($store, $search_param)) {
            $request->session()->flash('success', '店舗情報を更新しました。');
        } else {
            $request->session()->flash('success', '店舗情報更新が失敗しました。');
        } 
         
        return redirect()->route('store.my_account.info'); 
    } 

    public function addImg(Request $request)
    {   
        $store = $this->getAuthStore(); 
        $data['store_id'] = $store->id;
        $uploaded_main_files = $request->file('main_img');   
        if(!empty($uploaded_main_files)){ 
            $now = DateTime::createFromFormat('U.u', microtime(true)); 
            $new_filename = "main_".$now->format("YmdHisu"). '.'. $uploaded_main_files->getClientOriginalExtension(); 
            $uploaded_main_files->storeAs('public/temp', $new_filename); 
            $data['main_img'] = $new_filename;
            StoreService::doCreateOrUpdateMainImg($store, $data['main_img']);
        }

        $uploaded_sub_files = $request->file('sub_img');   
        if(!empty($uploaded_sub_files)){
            $sub_img=[]; 
            if(isset($uploaded_sub_files)){ 
                foreach($uploaded_sub_files as $file){  
                    if(!empty($file)){ 
                        $now = DateTime::createFromFormat('U.u', microtime(true)); 
                        $new_filename = "sub_".$now->format("YmdHisu"). '.'. $file->getClientOriginalExtension();  
                        $file->storeAs('public/temp', $new_filename); 
                        array_push($sub_img, $new_filename);
                    }
                }  
            } 
            $data['sub_img'] = $sub_img;   
            StoreService::doCreateOrUpdateSubImg($store, $data['sub_img']); 
        } 
        return response()->json(['result_code'=>'success']);
    } 

    public function deleteImg(Request $request)
    {   
        $store = $this->getAuthStore(); 
        $store_id = $store->id;  
        $file_name = $request->input('image_name');

        if($store_id) {
            $obj_image = StoreImg::where('store_id', $store_id)->where('img_name', $file_name)->first();
            if(is_object($obj_image) && $obj_image->delete()) {
                Storage::disk('stores')->delete($store_id . '/' . $file_name);
            }
        }
        Storage::disk('temp')->delete($file_name); 
        return response()->json(['sequence'=>$obj_image->sequence]);
    }

}
