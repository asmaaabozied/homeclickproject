<?php

namespace App\Http\Controllers\api\v1;

use App\Catogeryjob;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Image;
use App\Setting;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Modules\Geography\Entities\Geography;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;
use DB;
use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;
use function MongoDB\BSON\toJSON;


class CityController extends Controller
{


    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    public function listofcountry()
    {
        $cities = Geography::whereNull('parent_id')->get();


        return response()->json(['status' => 200, 'countries' => $cities]);

    }


    public function listofcities(Request $request)

    {


        $rule = [

            'country_id' => 'required',


        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $cities = Geography::where('parent_id', $request->country_id)->get();

        return response()->json(['status' => 200, 'cities' => $cities]);


    }

    public function jobs()
    {

        $catogeryjobs = Catogeryjob::where('status', 1)->get();

        $jobs = CityResource::collection($catogeryjobs);

        return response()->json(['status' => 200, 'jobs' => $jobs]);


    }
}
