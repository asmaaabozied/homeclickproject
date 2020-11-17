<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Image;
use App\Setting;
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
use function MongoDB\BSON\toJSON;


class SettingController extends Controller
{


    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    public function settings()
    {
        $settings = Setting::select('id', 'slug', 'value')->pluck('value', 'slug')->toArray();


        return response()->json(['status' => 200, 'settings' => $settings]);

    }


    public function social_login(Request $request)
    {

        if ($request->twitter_id) {


            $users = User::where('social_type', $request->twitter_id)->where('twitter_id', $request->twitter_id)->first();

            if ($users) {

                return response()->json(['status' => 200, 'user' => $users]);


            } else {


                $rule = [

                    'social_type' => 'required',
                    'name' => 'nullable',
                    'phone' => 'nullable',
                    'email' => 'nullable',
                    'twitter_id' => 'required|unique:users',
                ];

                $customMessages = [
                    'required' => __('validation.attributes.required'),
                ];

                $validator = validator()->make($request->all(), $rule, $customMessages);

                if ($validator->fails()) {

                    return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

                }


                $user = User::create([

                    'social_type' => $request->social_type,
                    'google_id' => $request->google_id ?? '',
                    'facebook_id' => $request->facebook_id ?? '',
                    'twitter_id' => $request->twitter_id ?? '',
                    'name' => $request->name ?? '',
                    'phone' => $request->phone ?? '',
                    'email' => $request->email ?? '',
                    'type' => 'User'

                ]);


            }

        }

        if ($request->facebook_id) {
            $users = User::where('social_type', $request->social_type)->where('facebook_id', $request->facebook_id)->first();

            if ($users) {

                return response()->json(['status' => 200, 'user' => $users]);


            } else {

                $rule = [

                    'social_type' => 'required',
                    'name' => 'nullable',
                    'phone' => 'nullable',
                    'email' => 'nullable',
                    'facebook_id' => 'required|unique:users',
                ];

                $customMessages = [
                    'required' => __('validation.attributes.required'),
                ];

                $validator = validator()->make($request->all(), $rule, $customMessages);

                if ($validator->fails()) {

                    return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

                }


                $user = User::create([

                    'social_type' => $request->social_type,
                    'google_id' => $request->google_id ?? '',
                    'facebook_id' => $request->facebook_id ?? '',
                    'twitter_id' => $request->twitter_id ?? '',
                    'name' => $request->name ?? '',
                    'phone' => $request->phone ?? '',
                    'email' => $request->email ?? '',
                    'type' => 'User'

                ]);


            }

        }

        if ($request->google_id) {

            $users = User::where('social_type', $request->social_type)->where('google_id', $request->google_id)->first();

            if ($users) {

                return response()->json(['status' => 200, 'user' => $users]);


            } else {
                $rule = [
                    'social_type' => 'required',
                    'name' => 'nullable',
                    'phone' => 'nullable',
                    'email' => 'nullable',
                    'google_id' => 'required|unique:users',
                ];

                $customMessages = [
                    'required' => __('validation.attributes.required'),
                ];

                $validator = validator()->make($request->all(), $rule, $customMessages);

                if ($validator->fails()) {

                    return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

                }


                $user = User::create([

                    'social_type' => $request->social_type,
                    'google_id' => $request->google_id ?? '',
                    'facebook_id' => $request->facebook_id ?? '',
                    'twitter_id' => $request->twitter_id ?? '',
                    'name' => $request->name ?? '',
                    'phone' => $request->phone ?? '',
                    'email' => $request->email ?? '',
                    'type' => 'User'

                ]);


            }


        }


        $token = $user->createToken('MyApp')->accessToken;


        $user->token = $token;


        return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success'), 'user' => $user]);


    }


}
