<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;  
use App\Models\User; 
use App\Service\UserService;
use Illuminate\Http\Request;
use Session;
use App\Http\Requests\Admin\UserRequest;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends AdminController
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

        Session::put('admin.users.index.params', $index_params);
  
        $users = UserService::doSearch($search_params)->paginate($this->per_page); 

        return view("{$this->platform}.users.index", [
            'users' => $users,
            'search_params'=>$search_params
        ]);
    }

    public function show(Request $request, User $user)
    {
        $index_params = Session::get('admin.users.index.params');
        return view("{$this->platform}.users.show", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }


    public function destroy(Request $request, User $user)
    { 
        $index_params = Session::get('admin.users.index.params');

        if (UserService::doDelete($user) ) {
            $request->session()->flash('success', 'ユーザーを削除しました。');
        } else {
            $request->session()->flash('error', 'ユーザー削除が失敗しました。');
        }
        return redirect()->route('admin.users.index', $index_params);
    }

    public function create()
    {
        $index_params = Session::get('admin.users.index.params');
        return view("{$this->platform}.users.create", [
            'index_params' => $index_params,
        ]);
    }

    public function edit(Request $request, User $user)
    {
        $index_params = Session::get('admin.users.index.params');
        return view("{$this->platform}.users.edit", [
            'user' => $user,
            'index_params' => $index_params,
        ]);
    }

    public function store(UserRequest $request)
    {
        $index_params = Session::get('admin.users.index.params');  

        if (UserService::doCreate($request->all())) {
            $request->session()->flash('success', 'ユーザーを追加しました。');
        } else {
            $request->session()->flash('error', 'ユーザー追加が失敗しました。');
        }
        return redirect()->route('admin.users.index', $index_params);

    }

    public function update(UserRequest $request, User $user)
    { 
        $index_params = Session::get('admin.users.index.params');
        if (UserService::doUpdate($user, $request->all())) {
            $request->session()->flash('success', 'ユーザーを更新しました。');
        } else {
            $request->session()->flash('success', 'ユーザー更新が失敗しました。');
        }
        return redirect()->route('admin.users.index', $index_params);
    }
 
}
