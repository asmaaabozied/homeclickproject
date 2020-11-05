<?php

namespace App\Http\Controllers\api\v1;

use App\Condition;
use App\ConditionTranslation;
use App\Http\Controllers\Controller;


use App\Uservistor;
use App\Visitor;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Modules\Geography\Entities\Geography;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;

use DB;
use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;
use function MongoDB\BSON\toJSON;


class VisitorController extends Controller
{



    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }



    public function AddVisitor(Request $request){

        $rule = [
            'device_id' => 'required',

        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

//            return responseJson(1,$validator->errors()->first(),$validator->errors());
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }



        $visitor= Uservistor::updateOrCreate([
            'ip'=>$request->device_id,

        ], [
            'ip'=>$request->device_id,
        ]);


        return response()->json(['status' => 200,'visitor'=>$visitor]);


    }


}
