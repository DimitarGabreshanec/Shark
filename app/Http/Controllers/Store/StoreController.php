<?php
namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests\Store\StoreRequest;
use App\Models\Store;
use App\Service\StoreService;

class StoreController extends Controller
{
    protected $platform = 'store';
    protected $per_page = 1000;
    protected $f_menu = ''; //お店で探す
    protected $fp_type = 1;

    public function __construct()
    {
        $this->middleware('auth:store');
        app('view')->composer('store.*', function ($view) {
            $f_menu = $this->f_menu; 
            $view->with(compact('f_menu'));
        });
    }  

    public function getAuthStore(){
        return Auth::guard('store')->user(); 
    }
}
