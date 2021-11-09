<?php

namespace App\Http\Controllers\Store;

use Session;
use Storage;
use DateTime;
use App\Models\Product;
use App\Models\ProductImg;
use App\Service\UserService;
use Illuminate\Http\Request;

use App\Service\ProductService;
use App\Service\FirebaseService;
use function GuzzleHttp\Promise\all;
use App\Http\Requests\Store\ProductRequest;

class ProductController extends StoreController
{
    public function create($product_type)
    {
        $index_params = Session::get('store.fix_products.index.params');
        if($product_type == config('const.product_type_code.fix')){
            $this->f_menu = config('const.footer_store_menu_kind_en.fix');
            return view("{$this->platform}.fix_products.create", [
                'index_params' => $index_params,
            ]);
        }
        else{
            $this->f_menu = config('const.footer_store_menu_kind_en.ec');
            return view("{$this->platform}.ec_products.create", [
                'index_params' => $index_params,
            ]);
        }
    }

    public function store(ProductRequest $request)
    {
        $index_params = $request->all();
        $uploaded_file = $request->file('main_img');
        if($uploaded_file != null){
            $new_filename = "main_".date("YmdHis"). '.'. $uploaded_file->getClientOriginalExtension();
            $uploaded_file->storeAs('public/temp', $new_filename);
            $index_params['main_img'] = $new_filename;
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
            $index_params['sub_img'] = $sub_img;
        }


        $store = $this->getAuthStore();
        $index_params['store_id'] = $store->id;
        if ($product = ProductService::doCreate($index_params)) {
            $this->sendPushNotification($product, 0);
            $request->session()->flash('success', '商品を登録しました。');
        } else {
            $request->session()->flash('error', '商品登録が失敗しました。');
        }
        if($index_params['type'] == config('const.product_type_code.fix')){
            return redirect()->route('store.fix_products.index', config('const.product_type_code.fix'));
        }
        else{
            return redirect()->route('store.ec_products.index', config('const.product_type_code.ec'));
        }
    }

    public function sendPushNotification(Product $product, $new_flag)
    {
        $image_path = ''; 
        if(is_object($product->obj_main_img) && Storage::disk('products')->has($product->id . '/' . $product->obj_main_img->img_name)){
            $image_path =  asset("storage/products/" . $product->id . '/' . $product->obj_main_img->img_name);
        }
        $store = $this->getAuthStore();
        $self_pos = $store->getPosition();
        $store_lat = isset($self_pos['lat']) ? $self_pos['lat'] : null;
        $store_lng = isset($self_pos['lng']) ? $self_pos['lng'] : null;
        $users = UserService::getImasuguUsers($store_lat,  $store_lng);
        $firebaseTokens=[];
        foreach($users as $user){
            $imasugu = $user->imasugu;
            $imasugu = json_decode($imasugu, true);
            $check = isset($imasugu['check']) ? $imasugu['check'] : 0;
            if($check){
                $isSelCategory = false;
                $categories = isset($imasugu['categories']) ? $imasugu['categories'] : null;
                foreach($store->arr_categories() as $value){
                    if(in_array($value, $categories)){
                        $isSelCategory = true;
                        break;
                    }
                }
                if($isSelCategory){
                    $fbs = $user->firebase_token;
                    if(!empty($fbs)){
                        array_push($firebaseTokens, $fbs);  
                    }

                }
            }
        }
        if(!empty($firebaseTokens)){ 
            FirebaseService::handle($firebaseTokens, $store, $product, $image_path, $new_flag); 
        }
    }

    public function edit(Request $request, Product $product, $product_type)
    {
        if($product_type == config('const.product_type_code.fix')){
            $this->f_menu = config('const.footer_store_menu_kind_en.fix');
            return view("store.fix_products.edit", [
                'product' => $product,
            ]);
        }else{
            $this->f_menu = config('const.footer_store_menu_kind_en.ec');
            return view("store.ec_products.edit", [
                'product' => $product,
            ]);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        $index_params = $request->all();
        $uploaded_file = $request->file('main_img');

        if($uploaded_file != null){
            $new_filename = "main_".date("YmdHis"). '.'. $uploaded_file->getClientOriginalExtension();
            $uploaded_file->storeAs('public/temp', $new_filename);
            $index_params['main_img'] = $new_filename;
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
            $index_params['sub_img'] = $sub_img;
        }


        $store = $this->getAuthStore();
        $index_params['store_id'] = $store->id;
        if ($product = ProductService::doUpdate($product, $index_params)) {
            $this->sendPushNotification($product, 1);
            $request->session()->flash('success', '商品を更新しました。');
        } else {
            $request->session()->flash('error', '商品更新が失敗しました。');
        }

        if($index_params['type'] == config('const.product_type_code.fix')){
            return redirect()->route('store.fix_products.index', config('const.product_type_code.fix'));
        }
        else{
            return redirect()->route('store.ec_products.index', config('const.product_type_code.ec'));
        }

    }

    public function index(Request $request, $product_type)
    {
        $index_params = $request->all(); 
        $store = $this->getAuthStore();
        $index_params['store_id'] = $store->id;
        $index_params['type'] = $product_type;
        $products = ProductService::doSearchProductInStore($index_params);
        if($product_type == config('const.product_type_code.fix')){
            $this->f_menu = config('const.footer_store_menu_kind_en.fix');
            return view("store.fix_products.show", [
                'products' => $products,
            ]);
        }
        else{
            $this->f_menu = config('const.footer_store_menu_kind_en.ec');
            return view("store.ec_products.show", [
                'products' => $products,
            ]);
        }

    }

    public function addImg(Request $request)
    {
        $product = Product::where('id', $request->input('product_id')) ->first();
        $uploaded_main_files = $request->file('main_img');
        if(!empty($uploaded_main_files)){
            $now = DateTime::createFromFormat('U.u', microtime(true));
            $new_filename = "main_".$now->format("YmdHisu"). '.'. $uploaded_main_files->getClientOriginalExtension();
            $uploaded_main_files->storeAs('public/temp', $new_filename);
            $data['main_img'] = $new_filename;
            ProductService::doCreateOrUpdateMainImg($product, $data['main_img']);
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
            ProductService::doCreateOrUpdateSubImg($product, $data['sub_img']);
        }
        return response()->json(['result_code'=>'success']);
    }

    public function deleteImg(Request $request)
    {
        $image_name = $request->input('image_name');
        $product_id = $request->input('product_id');
        $product = $product = Product::where('id', $product_id) ->first();
        $file_name = $request->input('image_name');
        if($product_id) {
            $obj_image = ProductImg::where('product_id', $product_id)->where('img_name', $file_name)->first();
            if(is_object($obj_image) && $obj_image->delete()) {
                Storage::disk('products')->delete($product_id . '/' . $file_name);
            }
        }
        Storage::disk('temp')->delete($file_name);
        return response()->json(['sequence'=>$obj_image->sequence]);

    }
}
