<?php

namespace App\Http\Controllers\Admin;
 
use App\Models\Category;
use App\Service\CategoryService; 
use Illuminate\Http\Request; 


class CategoryController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        return view('admin.category.index');
    }

    public function sequenceUpdate(Request $request)
    { 
        if(CategoryService::doSequenceUpdate($request->all()))
        {
            $request->session()->flash('success', '表示順序を更新しました。');
        }
        return redirect()->route('admin.category.index');
    }

    public function add(Request $request)
    {
        if($request->input('name') == null || $request->input('sequence') == null){
            $request->session()->flash('error', 'カテゴリー名または表示順序を正確に入力してください。');
            return response()->json(['result_code'=>'failed']);
        }

        if(CategoryService::doCreate($request->all()))
        {
            $request->session()->flash('success', 'カテゴリーを追加しました。');
            return response()->json(['result_code'=>'success']);
        }else{
            return response()->json(['result_code'=>'failed']);
        }

    }

    public function update(Request $request)
    {
        if($request->input('name') == null || $request->input('sequence') == null){
            $request->session()->flash('error', 'カテゴリー名または表示順序を入力してください。');
            return response()->json(['result_code'=>'failed']);
        }

        if(CategoryService::doUpdate($request->all()))
        {
            $request->session()->flash('success', 'カテゴリーを更新しました。');
            return response()->json(['result_code'=>'success']);
        }else{
            return response()->json(['result_code'=>'failed']);
        }

    }

    public function delete(Request $request)
    {
        $category = Category::where('id', $request->input('id')) ->first();
        if(CategoryService::doDelete($category)){
            $request->session()->flash('success', 'カテゴリーを削除しました。');
            return response()->json(['result_code'=>'success']);
        }else{
            return response()->json(['result_code'=>'failed']);
        }

    }

}
