<?php

namespace App\Http\Controllers\api\v1;

use App\Catogeryjob;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\NotificationResource;
use App\Image;
use App\Notification;
use App\Setting;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Modules\Geography\Entities\Geography;
use Spatie\Fractal\Fractal;
use Spatie\Fractalistic\ArraySerializer;
use Validator;
use Carbon\Carbon;
use Lang;
use LaravelLocalization;
use App\User;
use DB;

use Modules\UserManagement\Entities\UserMetas;

use App\PasswordReset;
use function MongoDB\BSON\toJSON;


class NotifyController extends Controller
{

    public function __construct()
    {
        $local = (!empty(Request()->route())) ? (Request()->route()->parameters()['locale']) : 'en';
        LaravelLocalization::setLocale($local);
    }


    public function shownotifications(Request $request)
    {


        $user_id = Auth::id();


        foreach (request()->segments(0) as $key) {
            if ($key == 'ar') {


                $notifications = Notification::where('user_id', $user_id)->orderBy('created_at', 'desc')->select('id', 'content_ar', 'type', 'created_at')->get();


            } elseif ($key == 'en') {

                $notifications = Notification::where('user_id', $user_id)->orderBy('created_at', 'desc')->select('id', 'content_en', 'type', 'created_at')->get();


            }
        }

       // $notificationTransformer = Fractal::create($notifications, new NotificationTransformer())->toArray();


//// to convert collections in api from data to any things
//        $manager = new Manager();
//        $manager->setSerializer(new ArraySerializer());
//        $resource = new Collection($notifications, new NotificationTransformer(), 'notifications');
//        $notificationsArray = $manager->createData($resource)->toArray();


        $notificationsArray = NotificationResource::collection($notifications);


        return response()->json(['status' => 200,  'notifications'=>$notificationsArray]);


    }


    public function countnotifications(Request $request)
    {

 $rule=[

           // 'firebase_token'=>'required',

        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator=validator()->make($request->all(),$rule,$customMessages);

        if($validator->fails()){
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())],422);


        }

        $user=Auth::user();

        if($request->firebase_token){

            $user->update(['firebase_token'=>$request->firebase_token]);
            $user->save();


        }



        $notifications = Notification::where('user_id', Auth::id())->where('status', 0)->count();


        return response()->json(['status' => 200, 'count_notifications' => $notifications]);


    }


    public function update_countnotifications(Request $request)
    {


        $rule = [
            'status' => 'required',
            'id' => 'required'
        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }

        $notification = Notification::find($request->id);


        $notification->status = $request->status;

        $notification->save();


        return response()->json(['status' => 200, 'notifications' => $notification]);


    }


}
