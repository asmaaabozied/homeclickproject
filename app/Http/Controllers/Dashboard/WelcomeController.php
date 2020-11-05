<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Catogery;
use App\Consultation;
use App\Lawercase;
use App\Product;
use App\User;
// use App\Supplier;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class WelcomeController extends Controller
{
    public function index()
    {

       $users_count = User::count();
        $catogery=Catogery::count();
        $products=Product::count();




        return view('dashboard.welcome', compact( 'products', 'catogery','users_count'));

    }//end of index

}//end of controller
