<?php
/**
 * Created by PhpStorm.
 * User: darany
 * Date: 6/1/2019
 * Time: 12:02 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;

class AdminController extends Controller
{
    protected $platform = 'admin';
    protected $per_page = 5;

    public function __construct()
    { 
       $this->middleware('auth:admin');
    }
}