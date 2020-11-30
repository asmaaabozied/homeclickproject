<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\SellerResource;
use App\Http\Resources\StoreResource;
use App\Image;
use App\Job;
use App\Offer;
use App\Product_User;
use App\Rating;
use App\Store;
use App\Subscribe;
use App\User_Store;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;
use DB;
use Hash;
use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;
use Illuminate\Support\Str;


class StoreController extends Controller
{


    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }



    public function offersellers(Request $request){


        $rule = [
            'store_id' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $offers=Offer::where('store_id',$request->store_id)->get();


        return response()->json(['status' => 200, 'offers' => $offers]);



    }


    public function filtersellers(Request $request){

        $rule = [
            'catogery_id' => 'required',
             'common'=>'required',
            'status'=>'required',
            'rating'=>'required',
            'geography_id'=>'required'

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

//        $sellers=Store::where('catogery_id',$request->catogery_id)->where('status',$request->status)->where('common',$request->common)->get();


        $sellers = Store::join('ratings', 'stores.id', '=', 'ratings.store_id')
            ->select('stores.*')//test
            ->groupBy('stores.id')
            ->havingRaw('AVG(ratings.rate) >= ?', [$request->rating])
            ->where('catogery_id',$request->catogery_id)
            ->where('status',$request->status)
            ->where('common',$request->common)
            ->where('geography_id',$request->geography_id)
            ->get();



        return response()->json(['status' => 200, 'sellers' => $sellers]);




    }

    public function add_ratingsellers(Request $request)
    {

        $user_id = Auth::id();

        $rule = [
            'seller_id' => 'required',
            'rate' => 'required'

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $rate = Rating::create(['store_id' => $request->seller_id, 'rate' => $request->rate, 'user_id' => $user_id, 'type' => 'Seller']);


        return response()->json(['status' => 200, 'rating_store' => $rate]);


    }


    public function details_sellers(Request $request)
    {


        $sellers = Store::where('id', $request->id)->where('status', 1)->get();

        $sellerss = SellerResource::collection($sellers);

        return response()->json(['status' => 200, 'sellers' => $sellerss]);


    }


    public function listofsellers(Request $request)
    {


        $sellers = Store::where('catogery_id', $request->category_id)->where('status', 1)->get();

        $sellerss = SellerResource::collection($sellers);

        return response()->json(['status' => 200, 'sellers' => $sellerss]);


    }

    public function filterseller(Request $request){

        $sellers = Store::orderBy('lat', 'desc')->orderBy('lon', 'desc')->where('status', 1)->get();

        $sellerss = SellerResource::collection($sellers);

        return response()->json(['status' => 200, 'sellers' => $sellerss]);


    }


    public function add_favourite_seller(Request $request)
    {


        $rule = [
            'store_id' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $user = Auth::user();


        $id = $request->store_id;


        $store = Store::find($id);


        $user->stores()->toggle($store);


        return response()->json(['status' => 200, 'store' => $store, 'user' => $user]);


    }


    public function favourite_seller()
    {

        $user_id = Auth::id();

        $user_seller = User_Store::where('user_id', $user_id)->get();

        $sellers = StoreResource::collection($user_seller);

        return response()->json(['status' => 200, 'sellers' => $sellers]);


    }


    public function add_stores(Request $request)
    {

        $rule = [

            'email' => 'max:254|unique:stores|email|required',
            'name' => 'required|string',
            'subscribe_id' => 'required',
            'phone' => 'required|min:9|unique:stores',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $store = Store::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subscribe_id' => $request->subscribe_id,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => 'Seller',

        ]);

        $user->save();


        $store->save();


        $token = $store->createToken('store')->accessToken;


        $store->token = $token;

        return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success'), 'store' => $store]);


    }


    public function listofsubscriptions()
    {


        $subscribe = Subscribe::select('id', 'created_at', 'updated_at')->get();

        return response()->json(['status' => 200, 'subscriptions' => $subscribe]);


    }


}
