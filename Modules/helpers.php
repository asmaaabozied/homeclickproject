<?php
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Mail;
use App\Mail\Verification;

define('Paginate_number',10);
define('Limit',10);
define('User_image_path','uploads/user_images/');
define('Admin_path','uploads/admins/');
define('Courses_path','uploads/courses_files/');
define('news_images','uploads/');
define('Chatting_image_path','uploads/user_chatting_images/');
define('consultation_files','uploads/consultation_files/');

define('days_list' , [
    'Saturday'=>'Saturday',
    'Sunday'=>'Sunday',
    'Monday'=>'Monday',
    'Tuesday'=>'Tuesday',
    'Wednesday'=>'Wednesday',
    'Thursday'=>'Thursday',
    'Friday'=>'Friday',

]);
define('consultation_status_type' , [
    'waiting'=>'Waiting',
    'reply'=>'contact with Client',
    'finished'=>'Finished',


]);

define('contact_type' , ['video','zoom','Webex Meeting','Amazon Chime','by Email']);


define('UserMetasKeys',['country'=>'text','city'=>'text','birthdate'=>'date','position'=>'text','firebase_token'=>'hidden']); // Keys To add To User Metas Table
define('AdminMetasKeys',['country'=>'text','city'=>'text','firebase_token'=>'hidden']); // Keys To add To User Metas Table


define('RoleModels' , [
    'roles','users','admins',
    'tags',
    'geographies',
    'categories',
//    'games',
    'news',
    'contactusmassages',
    'pages',
    'consultations',
]);
define('Maps' , ['create', 'read', 'update', 'delete']);
define('Mapss' , ['read']);


define('PhoneVerificationMessage' , 'Hi From Taqsema, Your Verification Code is: ');
define('PhoneResetPassMessage' , 'The Code For Resetting Your Password is: ');


function sendMessage($message, $recipients){
    try{
        // $account_sid = (!empty(getenv("TWILIO_SID")))? getenv("TWILIO_SID"): 'AC0d60cf212188e2ec13f961e1c7d29ba4';
        // $auth_token = (!empty(getenv("TWILIO_AUTH_TOKEN")))? getenv("TWILIO_AUTH_TOKEN"):'04ef711d0ff1c2fc2e77f8334a8f39e3';
        // $twilio_number = (!empty(getenv("TWILIO_NUMBER")))? getenv("TWILIO_NUMBER"):'+13072060754';
        $account_sid =env("TWILIO_SID") ;
        $auth_token =env("TWILIO_AUTH_TOKEN");
        $twilio_number = env("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
        return [true,"Okey"];
    }
    catch (Exception $e){
        //echo "Error: " . $e->getMessage();
        //echo "Error1: ";
        //report($e);
        return [false,$e->getMessage()];
    }
    catch (Services_Twilio_RestException $e){
        //echo "Error2: ". $e->getMessage();
        return [false,$e->getMessage()];
    }
    catch (TwilioException $e) {
        //echo "Error3: ". $e->getMessage();
         return [false,$e->getMessage()];
    }
}

function validationErrorsToString($errArray) {
    $valArr = array();
    foreach ($errArray->toArray() as $key => $value) {
        $newVal=(isset($valArr[$value[0]]))?$valArr[$value[0]].',' :'';
        $key=__('validation.attributes.'.$key);
        $valArr[$value[0]]=(!empty($valArr[$value[0]]))? $newVal.$key:$key ;

    }
    if(!empty($valArr)){
        $errorArr=array();
        foreach ($valArr as $errorMsg => $attributes) {

            $errorArr[]=__('validation.attributes.field')." (".$attributes.") ".$errorMsg;
        }
        $errStrFinal = implode(',', $errorArr);
    }
    return $errStrFinal;
}

function sendMail($to, $header, $subject,$body, $button){
    try{
        $data=array("name"=>$to[0]["name"],"header"=>$header , "body"=>$body , "button"=>$button,
                        "subject"=>$subject);

        Mail::to($to)->send(new Verification($data));
        if(count(Mail::failures())>0){
            return [false ,"Error"];
        }else{
            return[true,"okay"];
        }
    }
    catch (Exception $e){
        return [false,$e->getMessage()];
    }
}

function notifyByFirebase($title,$body,$tokens,$data = [])        // paramete 5 =>>>> $type
{
// https://gist.github.com/rolinger/d6500d65128db95f004041c2b636753a
// API access key from Google FCM App Console
    // env('FCM_API_ACCESS_KEY'));
//    $singleID = 'eEvFbrtfRMA:APA91bFoT2XFPeM5bLQdsa8-HpVbOIllzgITD8gL9wohZBg9U.............mNYTUewd8pjBtoywd';
//    $registrationIDs = array(
//        'eEvFbrtfRMA:APA91bFoT2XFPeM5bLQdsa8-HpVbOIllzgITD8gL9wohZBg9U.............mNYTUewd8pjBtoywd',
//        'eEvFbrtfRMA:APA91bFoT2XFPeM5bLQdsa8-HpVbOIllzgITD8gL9wohZBg9U.............mNYTUewd8pjBtoywd',
//        'eEvFbrtfRMA:APA91bFoT2XFPeM5bLQdsa8-HpVbOIllzgITD8gL9wohZBg9U.............mNYTUewd8pjBtoywd'
//    );
    $registrationIDs = $tokens;
// prep the bundle
// to see all the options for FCM to/notification payload:
// https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support
// 'vibrate' available in GCM, but not in FCM
    $fcmMsg = array(
        'body' => $body,
        'title' => $title,
        'sound' => "default",
        'color' => "#203E78"
    );
// I haven't figured 'color' out yet.
// On one phone 'color' was the background color behind the actual app icon.  (ie Samsung Galaxy S5)
// On another phone, it was the color of the app icon. (ie: LG K20 Plush)
// 'to' => $singleID ;      // expecting a single ID
// 'registration_ids' => $registrationIDs ;     // expects an array of ids
// 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
    $fcmFields = array(
        'registration_ids' => $registrationIDs,
        'priority' => 'high',
        'notification' => $fcmMsg,
        'data' => $data
    );
    $headers = array(
        'Authorization: key='.env('FIREBASE_API_ACCESS_KEY'),
        'Content-Type: application/json'
    );
    // if($type == 'client')
    // {
    //     $headers = array(
    //         'Authorization: key='.env('API_ACCESS_KEY_client'),
    //         'Content-Type: application/json'
    //     );
    // }
    // if($type == 'driver')
    // {
    //     $headers = array(
    //         'Authorization: key='.env('API_ACCESS_KEY_driver'),
    //         'Content-Type: application/json'
    //     );
    // }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function sendNotification($tokens, $title, $body, $data,$sendBackGround=false){
    $notification = array('title' => $title,
                         'body' => $body,
                        'sound' => 'default',
                        'icon'=> 'https://taqsema.com/wp-content/uploads/2018/07/header-logo-1.png',
                        // 'click_action'=> 'http://localhost:5000',
                        // "icon" => url('/logo.png')
                        'content_available' => true,
                        'badge' => '1');
    if(!empty($data)){
        $data->title= $title;
        $data->body = $body;
    }

    $url = 'https://fcm.googleapis.com/fcm/send';
    if(is_array($tokens))
      $fields = array('registration_ids' => $tokens,'data' => $data,'notification' => $notification);
    else
      $fields = array('to' => $tokens ,'data' => $data,'notification' => $notification);
      if($sendBackGround) unset($fields['notification']);

    $key = env("FIREBASE_SERVER_KEY");
    $headers = array('Authorization: key='.$key,'Content-type: Application/json');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function createTopic($topic,$id) {
  $url = 'https://iid.googleapis.com/iid/v1/'.$id.'/rel/topics/'.$topic;
  $key = env("FIREBASE_SERVER_KEY");
  $headers = array('Authorization: key=' . $key,'Content-type: Application/json','Content-Length: 0');
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_POST, true );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
  $result = curl_exec ( $ch );
  curl_close ( $ch );
  return $result;
}

function removeFileByPath($path){
    try {unlink($path);}
    catch ( \Throwable $e ) {error_log("try to delete Image : ".$e);}
    // try { File::delete($path);}
    //catch ( \Throwable $e ) {error_log("try to delete File : ".$e);}

}//end of removeFile

?>
