<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\ProductImg;
use App\Models\StoreImg;
use App\Service\ProductService;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Service\StoreService;
use Illuminate\Http\Request;
use Session;
use DateTime;
use Storage;

class ProductController extends AdminController
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
        $stores = StoreService::getAllStores();

        Session::put('admin.products.index.params', $index_params);

        $products = ProductService::doSearch($search_params)->paginate($this->per_page);

        return view("admin.products.index", [
            'products' => $products,
            'search_params'=>$search_params,
            'stores_params' => $stores,
        ]);
    }

    public function create()
    {
        $index_params = Session::get('admin.products.index.params');
        $stores = StoreService::getAllStores();
        return view("{$this->platform}.products.create", [
            'index_params' => $index_params,
            'stores_params' => $stores,
        ]);
    }

    public function show(Request $request, Product $product)
    {
        $index_params = Session::get('admin.products.index.params');
        return view("{$this->platform}.products.show", [
            'product' => $product,
            'index_params' => $index_params,
        ]);
    }

    public function edit(Request $request, Product $product)
    {
        $index_params = Session::get('admin.products.index.params');
        $stores = StoreService::getAllStores();
        return view("{$this->platform}.products.edit", [
            'product' => $product,
            'index_params' => $index_params,
            'stores_params' => $stores,
        ]);
    }

    public function store(ProductRequest $request)
    {
        $index_params = Session::get('admin.products.index.params');
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
 
        if (ProductService::doCreate($data)) {
            $request->session()->flash('success', '商品を追加しました。');
        } else {
            $request->session()->flash('error', '商品追加が失敗しました。');
        }
        return redirect()->route('admin.products.index', $index_params);

    }

    public function update(ProductRequest $request, Product $product)
    {  
        $index_params = Session::get('admin.products.index.params'); 
        if (ProductService::doUpdate($product, $request->all())) {
            $request->session()->flash('success', '商品を更新しました。');
        } else {
            $request->session()->flash('success', '商品更新が失敗しました。');
        }
        return redirect()->route('admin.products.index', $index_params);
    }


    public function destroy(Request $request, Product $product)
    {
        $index_params = Session::get('admin.products.index.params');

        if (ProductService::doDelete($product) ) {
            $request->session()->flash('success', '商品を削除しました。');
        } else {
            $request->session()->flash('error', '商品削除が失敗しました。');
        }
        return redirect()->route('admin.products.index', $index_params);
    }

    public function uploadImage(Request $request)
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
