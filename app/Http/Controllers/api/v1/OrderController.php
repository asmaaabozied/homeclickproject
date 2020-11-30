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
use App\Order;
use App\Order_Product;
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


class OrderController extends Controller
{
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    public function canceled_order(Request $request)
    {

        $rule = [

            'order_id' => 'required',


        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $order = Order::find($request->order_id);

        $order->update(['status' => 'canceled']);

        return response()->json(['status' => 200, 'message' => __('site.messages.ordersuccess')]);


    }

    public function add_order(Request $request)
    {

        $rule = [

            'products' => 'required',

            'address_id' => 'required',

            'payment_id' => 'required',
            'payment_type'=>'required',

            'total' => 'required',

            'capon' => 'required'


        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $order = Order::create([

            'address_id' => $request->address_id,

            'payment_id' => $request->payment_id,

            'total' => $request->total,

            'capon'=>$request->capon,

            'payment_type'=>$request->payment_type,

            'number'=>rand(11111, 99999),


            'user_id'=>Auth::id()

        ]);

        $ordersArray = [];
        foreach ($request->products as $product) {
            $productId = $product['product'];
            $qty = $product['quantity'];

            $order->products()->attach($productId, ['quantity' => $qty]);
            array_push($ordersArray, $order);
        }

        return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success')]);


    }


    public function show_orders(Request $request)
    {


        $rule = [
            'type' => 'required',


        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }




        $orders = Order::where('user_id', Auth::id())->where('status', $request->type)->with('products')->get();

        return response()->json(['status' => 200, 'orders' => $orders]);


    }


    public function details_orders(Request $request)
    {

        $orders = Order::where('user_id', Auth::id())->where('id', $request->id)->with('products')->get();

        return response()->json(['status' => 200, 'orders' => $orders]);

    }
}
