<?php

namespace App\Http\Controllers\api\v1;

use App\Capon;
use App\Cart;
use App\Category;
use App\Consultation;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductssResource;

use App\Http\Resources\DetailsProductResource;
use App\Offer;
use App\Product;
use App\Product_User;

use App\Rating;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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


class CartController extends Controller
{
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    public function add_cart(Request $request)
    {


        $user_id = Auth::id();


        $rule = [
            'name' => 'required',
            'number' => 'required',
            'expire_date' => 'required',
            'cvv' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $cart = Cart::create([

            'name' => $request->name,
            'number' => $request->number,
            'expire_date' => $request->expire_date,
            'cvv' => $request->cvv,
            'user_id' => $user_id

        ]);


        return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success'), 'cart' => $cart]);


    }


    public function show_cart(Request $request)
    {

        $user_id = Auth::id();

        $cart = Cart::where('user_id', $user_id)->get();

        return response()->json(['status' => 200, 'carts' => $cart]);


    }


    public function check_code_capons(Request $request)
    {


        $rule = [
            'code' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $capon = Capon::where('code', $request->code)->select('id', 'discount', 'created_at')->first();

        if ($capon) {

            return response()->json(['status' => 200, 'caponss' => $capon]);


        } else {

            return response()->json(['status' => 422, 'message' => __('site.messages.user_codeInvalid'),], 422);


        }


    }


}
