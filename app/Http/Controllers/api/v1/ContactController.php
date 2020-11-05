<?php

namespace App\Http\Controllers\api\v1;

use App\Catogeryjob;
use App\Http\Controllers\Controller;
use App\Http\Resources\ComplainResource;
use App\Image;
use App\Contact;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;
use DB;
use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;


class ContactController extends Controller
{



    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }


    public function listofcomplain(){


        $catogeryjobs=Catogeryjob::where('status',1)->get();

        $jobs= ComplainResource::collection($catogeryjobs);

        return response()->json(['status'=>200,'complain'=>$jobs]);



    }


    public function contact_us(Request $request)
    {

        $user_id = Auth::id();

        $rule = [
         //   'email' => 'required',
         'email'    => 'max:254|unique:contacts|email|required',
            'catogeryjob_id' => 'required|integer',
            'name' => 'required',
            'message' => 'required',
           // 'user_id' => 'required',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

//            return responseJson(1,$validator->errors()->first(),$validator->errors());
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        if(isset(auth('api')->user()->id)and !empty(auth('api')->user()->id)) {
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'catogeryjob_id' => $request->catogeryjob_id,
                'message' => $request->message,

                'user_id' => auth('api')->user()->id,
            ]);

        }else{
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'catogeryjob_id' => $request->catogeryjob_id,
                'message' => $request->message,

            ]);

        }
        return response()->json(['status' => 200,'message' =>__('site.messages.opertaion_success'), 'contact' => $contact]);

    }




}
