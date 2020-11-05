<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
// use App\User;
use Modules\UserManagement\Entities\UserMetas;

class NotificationController extends Controller
{
    //
    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }

    public function sendNotify(Request $request){

        // $remember =(!empty(request('remember')) && request('remember')=='on') ? true : false;
        $rules = [
            'receiver_id' => 'required',
            'data' => 'required',
        ];
        $input = $request->only('receiver_id', 'title','body','data');
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];
        $validator = Validator::make($input, $rules, $customMessages);
        if ($validator->fails()) {
            $errorStr=validationErrorsToString($validator->errors());
            return response()->json(['status' => 422, 'message' =>$errorStr ],422);
        }

        $sender_id =auth()->user()->id;
        $receiver_id=request('receiver_id');
        $title=request('title');
        $body=request('body');
        $data=(!empty(request('data')) )? json_decode(request('data')):[];
        $userMetas_info=UserMetas::select("attr_key","attr_value")
                            ->where([["user_id", $receiver_id] ,["attr_key", "device_token"]])
                            ->orWhere([["user_id", $receiver_id] ,["attr_key","notify"]])
                            ->pluck("attr_value","attr_key");
        $device_token=(!empty($userMetas_info["device_token"]))?$userMetas_info["device_token"]:"";
        //check If UserNotification Off
        $option_Notify=true;
        if(empty($userMetas_info["notify"]))true:false;
        $send_notification_background=($option_Notify)?false: true;


        if(empty($device_token)){
            return response()->json(['status' => 422, 'message' =>__('site.messages.user_notExist') ],422);
        }
        else{

            $result=sendNotification($device_token, $title, $body,$data,$send_notification_background);
            return response()->json(['status'=>200 ,'data'=>json_decode($result)]);

        }
    }

}
