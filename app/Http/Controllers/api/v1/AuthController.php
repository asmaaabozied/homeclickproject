<?php

namespace App\Http\Controllers\api\v1;

use App\Address;
use App\Http\Controllers\Controller;

use App\Http\Resources\UserResource;
use App\Mail\Resetpassword;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Image;
use Modules\Geography\Entities\Geography;
use Validator;
use Carbon\Carbon;
use Lang;

use LaravelLocalization;

use App\User;
use DB;
use Mail;
use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;


class AuthController extends Controller
{


    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }



    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    public function uploadimages(Request $request)
    {
        $rule = [
            'type' => 'required|string',
            'images' => 'required'
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

//            return responseJson(1,$validator->errors()->first(),$validator->errors());
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


//         $image=Image::create([ 'imageable_type'=>'App\User' ]);

        if ($request->file('images')) {

            $imagess = $request->file('images');
            $imagesArr = [];
            foreach ($imagess as $images) {
                $img = "";
                $img = $this->str_random(4) . $images->getClientOriginalName();
                $originname = time() . '.' . $images->getClientOriginalName();
                $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
                $filename = $images->hashName();
                $extention = pathinfo($originname, PATHINFO_EXTENSION);
                $img = $filename;

                $destintion = 'uploads';
                $images->move($destintion, $img);



//                    App\User
                $imagesArr[] = [
                    'image' => $img,
                    'imageable_type' =>'App/'. $request->type
                ];


                Image::insert($imagesArr);

            }


            return response()->json(['status' => 200, 'images' => $imagesArr]);


        }
    }


    public function new_password(Request $request)
    {


        $rule = [
            'code' => 'required',
            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[!-\/:-@\[-`{-~]/',
            'c_password' => 'required_with:password|same:password',


        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $user = User::where('code', $request->code)->where('code', '!=', 0)->first();

        if ($user) {


            $update = $user->update(['password' => bcrypt($request->password), 'code' => null]);

            if ($update) {

                return response()->json(['status' => 200, 'message' => __('site.messages.resetpassword')]);


            } else {

                return response()->json(['status' => 422, 'message' => __('site.messages.error')], 422);


            }


        } else {


            return response()->json(['status' => 422, 'message' => __('site.messages.user_codeInvalid')], 422);


        }


    }


    public function reset_password(Request $request)
    {

        if (isset($request->phone)) {


            $rule = [

                'phone' => 'required|min:9',


            ];
            $customMessages = [
                'required' => __('validation.attributes.required'),
            ];

            $validator = validator()->make($request->all(), $rule, $customMessages);

            if ($validator->fails()) {

                return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

            }


            $user = User::where('phone', $request->phone)->first();


        } elseif (isset($request->email)) {

            $rule = [

                'email' => 'max:254|email|required',


            ];
            $customMessages = [
                'required' => __('validation.attributes.required'),
            ];

            $validator = validator()->make($request->all(), $rule, $customMessages);

            if ($validator->fails()) {

                return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

            }


            $user = User::where('email', $request->email)->first();


        }


        if ($user) {

            $code = rand(1111, 9999);

            $update = $user->update(['code' => $code]);

            if ($update) {


                // smsm


                // send email

                Mail::to($user->email)->send(new Resetpassword($user));


                return response()->json(['status' => 200, 'message' => __('site.messages.validreset')]);


            }


        } else {

            return response()->json(['status' => 422, 'message' => __('site.messages.invalidUserId'),],422);


        }


    }


    public function showprofile()
    {

        $user_id = Auth::id();

        $user = User::where('id', $user_id)->select('id', 'name', 'email', 'phone', 'address', 'created_at', 'updated_at', 'image')
            ->first();

        return response()->json(['status' => 200, 'user' => $user]);


    }


    public function updateprofile(Request $request)
    {

        if (isset($request->old_name)) {


            $rule = [

                'old_name' => 'required|string',
                'new_name' => 'required|string',


            ];
            $customMessages = [
                'required' => __('validation.attributes.required'),
            ];

            $validator = validator()->make($request->all(), $rule, $customMessages);

            if ($validator->fails()) {

                return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

            }

            $user = User::find(Auth::id());


            if ($request->old_name == $user->name) {

                $user->update(['name' => $request->new_name]);

                return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success'), 'user' => $user]);

            } else {

                return response()->json(['status' => 422, 'message' => __('site.messages.user_Invalid')], 422);


            }


        } else {


            $rule = [
                'email' => 'max:254|email|required',
//             'name'    => 'required|string',
                'phone' => 'required|min:9',
                'address' => 'required|string',
                'image' => 'required',

            ];
            $customMessages = [
                'required' => __('validation.attributes.required'),
            ];

            $validator = validator()->make($request->all(), $rule, $customMessages);

            if ($validator->fails()) {

                return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

            }

            $user = User::findorfail(Auth::id());


            $user->image = asset('uploads/' . $request->image);
            $user->email = isset($request->email) ? $request->email : $user->email;
            $user->phone = isset($request->phone) ? $request->phone : $user->phone;
            $user->address = isset($request->address) ? $request->address : $user->address;
            $user->remember_token = Str::random(60);


            $token = $user->createToken('MyApp')->accessToken;

            $user->token = $token;

            $user->save();


            if ($request->image) {

                \App\Image::updateOrCreate([
                    'imageable_type' => 'App/User',
                ], [
                    'imageable_id' => $user->id,
                    'imageable_type' => 'App\User',
                    'image' => $request->image,

                ]);
            }


            return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success'), 'user' => $user]);
        }


    }


    public function logout()
    {

        $user = Auth::user()->revoke();

        return $this->responseJson(200, __('site.messages.success'), $user);


    }


    public function register(Request $request)
    {

        $rule = [
            'email' => 'max:254|unique:users|email|required',
            'name' => 'required|string',
            'phone' => 'required|min:9|unique:users',

            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[!-\/:-@\[-`{-~]/',
//            'c_password'=>'required_with:password|same:password',
           'firebase_token'=>'required',

        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,


            'password' => bcrypt($request->password),
           'firebase_token'=>$request->firebase_token,
            'remember_token' => Str::random(60)
        ]);

        $image = \App\Image::create([
//            'imageable_id'=>$user->id,
            'imageable_type' => 'App/User',
            'image' => 'default.png'

        ]);
        $user->save();

        $user->image = asset('uploads/' . $image->image);

        $token = $user->createToken('MyApp')->accessToken;


        $user->token = $token;



//        $user->save();

        return response()->json(['status' => 200, 'message' => __('site.messages.opertaion_success'), 'user' => $user]);
    }

    public function login(Request $request)
    {

        $rule = [
            'email' => 'email|required',
            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[!-\/:-@\[-`{-~]/',
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

//            return responseJson(1,$validator->errors()->first(),$validator->errors());
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $password = $request->password;
        $email = $request->email;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            if($request->firebase_token){

                $user->update(['firebase_token'=>$request->firebase_token]);
                $user->save();


            }
            $user->firebase_token=isset($request->firebase_token) ? $request->firebase_token : $user->firebase_token;

            $token = $user->createToken('MyApp')->accessToken;

            $user->token = $token;
            return response()->json(['status' =>200, 'message' => __('site.messages.opertaion_success'), 'user'=>$user]
            );


        } else {
            return response()->json(['status' => 422, 'message' => __('site.messages.user_loginInvalid')], 422);

        }

    }


    public function create_address(Request $request)
    {


        $user_id = Auth::id();


        $rule = [

            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|min:9',
            'type' => 'required',
            'country_id' => 'required|integer',
            'location' => 'required|string',
            'details' => 'required',

        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $address = Address::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'address' => $request->address,
            'type' => $request->type,
            'details' => $request->details,
            'geography_id' => $request->country_id,
            'location' => $request->location,
            'user_id' => $user_id
        ]);


        return response()->json(['status' =>200, 'message' => __('site.messages.opertaion_success'), 'address'=>$address]);

    }

    public function update_address(Request $request)
    {

        $user_id = Auth::id();


        $rule = [

            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|min:9',
            'type' => 'required',
            'country_id' => 'required|integer',
            'location' => 'required|string',
            'details' => 'required',

        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }


        $address = Address::where('user_id', $user_id)->first();


        $address->firstname = isset($address->firstname) ? $request->firstname : $address->firstname;
        $address->lastname = isset($address->lastname) ? $request->lastname : $address->lastname;
        $address->details = isset($address->details) ? $request->details : $address->details;
        $address->location = isset($address->location) ? $request->location : $address->location;
        $address->phone = isset($request->phone) ? $request->phone : $address->phone;
        $address->geography_id = isset($request->country_id) ? $request->country_id : '';
        $address->address = isset($request->address) ? $request->address : $address->address;
        $address->type = isset($request->type) ? $request->type : $address->type;
        $address->save();

        return response()->json(['status' =>200,'message' => __('site.messages.opertaion_success'),'address'=> $address]);


    }


    public function show_address()
    {

        $address = Address::where('user_id', Auth::id())->select('type','id', 'location', 'created_at')->get();

        return response()->json(['status' => 200, 'addresses' => $address]);


    }


}
