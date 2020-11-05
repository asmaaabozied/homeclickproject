<?php

namespace App\Http\Controllers\api\v1;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;

use Hash;

use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;
use Illuminate\Support\Str;


class productController extends Controller
{
    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    public function filterproduct(Request $request)
    {


//        $user_id = Auth::id();

            $rule = [
                   'catogery_id' => 'required',
                   'price' => 'required',
                   'common'=>'required',
                   'rating'=>'required'

               ];

               $customMessages = [
                   'required' => __('validation.attributes.required'),
               ];

               $validator = validator()->make($request->all(), $rule, $customMessages);

               if ($validator->fails()) {

                   return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

               }

//       $products=Product::where('catogery_id',$request->catogery_id)->where('price',$request->price)->where('common',$request->common)->get();

        $products = Product::join('ratings', 'products.id', '=', 'ratings.product_id')
            ->select('products.*')//test
            ->groupBy('products.id')
            ->havingRaw('AVG(ratings.rate) >= ?', [$request->rating])
            ->where('catogery_id',$request->catogery_id)
            ->where('price',$request->price)
            ->where('common',$request->common)
            ->get();


        return response()->json(['status' => 200, 'products' => $products]);


    }

    public function add_ratingproducts(Request $request)
    {

        $user_id = Auth::id();

        $rule = [
            'product_id' => 'required',
            'rate' => 'required'

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $rate = Rating::create(['product_id' => $request->product_id, 'rate' => $request->rate, 'user_id' => $user_id, 'type' => 'Product']);


        return response()->json(['status' => 200, 'rating_product' => $rate]);


    }

    public function listofproducts(Request $request)
    {

        $products = Product::where('catogery_id', $request->catogery_id)->get();


        $productss = ProductssResource::collection($products);

        return response()->json(['status' => 200, 'products' => $productss]);


    }

    public function listofcatogeries_product()
    {

        $catogeries = Category::get();


        return response()->json(['status' => 200, 'products_catogeries' => $catogeries]);


    }

    public function detailsproduct(Request $request)
    {

        $product = Product::where('id', $request->id)->get();

        $products = DetailsProductResource::collection($product);


        return response()->json(['status' => 200, 'product' => $products]);


    }

    public function listofoffers()
    {


        $offers = Offer::where('product_id', '!=', null)->get();


        $products = OfferResource::collection($offers);


        return response()->json(['status' => 200, 'products' => $products]);


    }


    public function favourite_product()
    {


        $user_id = Auth::id();

        $user_product = Product_User::where('user_id', $user_id)->get();

        $products = ProductResource::collection($user_product);

        return response()->json(['status' => 200, 'products' => $products]);


    }


    public function add_favourite_product(Request $request)
    {


        $rule = [
            'product_id' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $user = Auth::user();


        $id = $request->product_id;

        $product = Product::find($id);


        $user->products()->toggle($product);


        return response()->json(['status' => 200, 'product' => $product, 'user' => $user]);


    }


}
