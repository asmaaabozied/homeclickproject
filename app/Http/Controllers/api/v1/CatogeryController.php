<?php

namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Http\Resources\OffercatogeryResource;
use App\Http\Resources\ParentcatogeryResource;
use App\Http\Resources\SubcatogeryResource;
use App\Offer;
use Illuminate\Http\Response;

use App\Http\Resources\CatogeryResource;
use App\Catogery;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;
use DB;


class CatogeryController extends Controller
{



    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }


    public function listofofferofcatogeries()
    {


        $offers = Offer::where('catogery_id','!=',null)->get();


        $offerss = OffercatogeryResource::collection($offers);

        return response()->json(['status' => 200, 'offers_categories' => $offerss]);


    }

    public function listofcatogery(Request $request){

        if(isset($request->name) and !empty($request->name)){

            $catogery=Catogery::whereTranslationLike('name',"%".$request->name."%") ->get();

        }else{

            $catogery=Catogery::select('id' ,'icons','created_at','updated_at','parent_id','type')->where('type',1)->get();

        }


        return response()->json(['status'=>200,'categories'=>$catogery]);
    }






}
