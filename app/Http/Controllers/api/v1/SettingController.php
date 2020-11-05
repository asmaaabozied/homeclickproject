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
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }


    public function settings()
    {
        $settings = Setting::select('id','slug', 'value')->pluck( 'value' ,'slug')->toArray();

//        $settings = Setting::select('id','slug', 'value')->get()->toArray();

        return response()->json(['status' => 200, 'settings' => $settings]);

    }
}
