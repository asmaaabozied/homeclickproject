<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {

        return $response()->json(['status'=>200,'message'=>__($response)]);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {

        return $response()->json(['status'=>422,'message'=>__($response)]);

    }


//    protected function rules()
//    {
//        return [
//            'token' => 'required',
//            'email' => 'required|email',
//            'password' => 'required|confirmed|min:6',
//        ];
//    }

    }
