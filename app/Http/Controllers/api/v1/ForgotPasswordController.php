<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Validator;
use LaravelLocalization;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }

//    public function forgot(Request $request) {
//        $credentials = request()->validate(['email' => 'required|email']);
////        dd($credentials);
//        Password::sendResetLink($credentials);
//
//        return response()->json(["msg" => 'Reset password link sent on your email id.']);
//    }
//
//
//    public function reset() {
//        $credentials = request()->validate([
//            'email' => 'required|email',
//            'token' => 'required|string',
//            'password' => 'required|string|confirmed'
//        ]);
//
//        $reset_password_status = Password::reset($credentials, function ($user, $password) {
//            $user->password = $password;
//            $user->save();
//        });
//
//        if ($reset_password_status == Password::INVALID_TOKEN) {
//            return response()->json(["msg" => "Invalid token provided"], 400);
//        }
//
//        return response()->json(["msg" => "Password has been successfully changed"]);
//    }

    public function sendResetLinkEmail(Request $request)
    {
        //$this->validateEmail($request);
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
//            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[!-\/:-@\[-`{-~]/',

        ]);
        if($validator->passes()){
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);

        }else{
            return response()->json(['status'=>422,'message'=>validationErrorsToString($validator->errors())], 422);
        }

    }



    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['status'=>200,'message'=>__($response)]);
    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(['state'=>422,'error'=>__($response)],422);
    }


}
