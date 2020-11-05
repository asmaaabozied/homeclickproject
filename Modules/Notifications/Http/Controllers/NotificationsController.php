<?php

namespace Modules\Notifications\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Notifications\Http\Requests\NotifyRequest;
use Modules\UserManagement\Entities\UserMetas;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
     $owners=  User::where('type', '=', 'admin')->get();
     $users=User::where('type', '=', 'User')->get();
        $user_id=1;
        return view('notifications::notify.index',compact('users', 'owners','user_id'));
    }
    protected $serverKey;




    public function sendPush ( NotifyRequest $request)
    {

            //sends newsletter to selected users
            if ($request->has('user_emails')) {

                    $array['receiver_ids'] = $request->user_emails ;
                    $array['title'] = $request->title;
                    $array['body'] = $request->body;
                    $this->notification($array);

            }

            //sends newsletter to subscribers
            if ($request->has('owner_emails')) {
                $array['receiver_ids'] = $request->owner_emails ;
                $array['title'] = $request->title;
                $array['body'] = $request->body;
                $this->notification($array);


            }


        session()->flash('success',__('site.messages.send_massage_successful'));
        return redirect()->route('dashboard.welcome');


    }


    public function notification(array $array)
    {
        $receiver_ids=$array['receiver_ids'];
        $title=$array['title'];
        $body=$array['body'] ;
        $data=[];

        $userMetas_info=UserMetas::whereIn("user_id", $receiver_ids)
                        ->where("attr_key","firebase_token")
                        ->pluck('attr_value')->toArray();

        $device_tokens=(!empty($userMetas_info))?$userMetas_info:"";
        //check If UserNotification Off
        $option_Notify=true;

        $send_notification_background=($option_Notify)?false: true;

        if(empty($device_tokens)){
            session()->flash('error',__('site.messages.user_notExist'));

            return back();
        }
        else{
            $result=sendNotification($device_tokens, $title, $body,$data,$send_notification_background);
//            session()->flash('success',__('site.messages.send_massage_successful'));
//            return response()->json(['status'=>200 ,'data'=>json_decode($result)]);

        }
    }

}
