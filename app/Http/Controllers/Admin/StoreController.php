<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Http\Requests\Admin\StoreRequest;
use App\Models\StoreImg;
use App\Notifications\Store\StoreCreateNotification;
use App\Notifications\User\ResetPasswordNotification;
use App\Service\StoreService;
use Illuminate\Http\Request;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use DateTime; 

class StoreController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $index_params = $request->all();
        $search_params = $request->input('search_params');

        Session::put('admin.stores.index.params', $index_params);

        $stores = StoreService::doSearch($search_params)->paginate($this->per_page);

        return view("admin.stores.index", [
            'stores' => $stores,
            'search_params'=>$search_params
        ]);
    }

    public function create()
    {
        $index_params = Session::get('admin.stores.index.params');
        $index_params['status'] = config('const.store_status_code.registered');
        return view("{$this->platform}.stores.create", [
            'index_params' => $index_params,
        ]);
    }

    public function show(Request $request, Store $store)
    {
        $index_params = Session::get('admin.stores.index.params'); 
        return view("{$this->platform}.stores.show", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }

    public function edit(Request $request, Store $store)
    {
        $index_params = Session::get('admin.stores.index.params');
        return view("{$this->platform}.stores.edit", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }

    public function store(StoreRequest $request)
    {
        $index_params = Session::get('admin.stores.index.params');
        $data = $request->all();
        $uploaded_main_files = $request->file('main_img');
        if(!empty($uploaded_main_files)){
            $now = DateTime::createFromFormat('U.u', microtime(true));
            $new_filename = "main_".$now->format("YmdHisu"). '.'. $uploaded_main_files->getClientOriginalExtension();
            $uploaded_main_files->storeAs('public/temp', $new_filename);
            $data['main_img'] = $new_filename;
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
        }

        if (StoreService::doCreate($data)) {
            $request->session()->flash('success', '店舗を追加しました。');
        } else {
            $request->session()->flash('error', '店舗追加が失敗しました。');
        }
        return redirect()->route('admin.stores.index', $index_params);

    }

    public function update(StoreRequest $request, Store $store)
    {
        $index_params = Session::get('admin.stores.index.params');

        if (StoreService::doUpdate($store, $request->all())) {
            $request->session()->flash('success', '店舗を更新しました。');
        } else {
            $request->session()->flash('success', '店舗更新が失敗しました。');
        }
        return redirect()->route('admin.stores.index', $index_params);
    }


    public function destroy(Request $request, Store $store)
    {
        $index_params = Session::get('admin.stores.index.params');

        if (StoreService::doDelete($store) ) {
            $request->session()->flash('success', '店舗を削除しました。');
        } else {
            $request->session()->flash('error', '店舗削除が失敗しました。');
        }
        return redirect()->route('admin.stores.index', $index_params);
    }

    public function uploadImage(Request $request)
    {
        $data['store_id'] = $request->input('store_id');
        $store = Store::where('id', $request->input('store_id')) ->first();
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
        $store_id = $request->input('store_id');
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
