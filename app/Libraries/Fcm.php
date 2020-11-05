<?php


namespace App\Libraries;

use App\Admin;
use App\Employee;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

class Fcm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Fcm';
    }

    static function sendToOne($token,$info=[]){

        $token= $token;
        $title= isset($info['title']) ? $info['title'] : "962GS";
        $message= $info["body"];

        $token = htmlspecialchars($token,ENT_COMPAT);
        $title = htmlspecialchars($title,ENT_COMPAT);
        $message = htmlspecialchars($message,ENT_COMPAT);

        $data = array(
            "to" => "$token",
            "notification" => array(
                "title" => "$title",
                "body" => "$message",
                "icon" => "https://example.com/icon.png"
            ));

        $data_string = json_encode($data);

        $url = "https://fcm.googleapis.com/fcm/send";

        $headers = array
        (
            'Authorization: key=' . env('FIREBASE_API_ACCESS_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);

        $result = curl_exec($ch);

        if(json_decode($result)->success==1){
            curl_close ($ch);
            return true;
        }else{
            curl_close ($ch);
            dd($result);
            return false;
        }


    }


    static function sendToMany($tokens=[],$info=[]){

        $tokens= $tokens;
        $title= isset($info['title']) ? $info['title'] : "962GS";
        $message= $info["body"];

        //$tokens = htmlspecialchars($tokens,ENT_COMPAT);
        $title = htmlspecialchars($title,ENT_COMPAT);
        $message = htmlspecialchars($message,ENT_COMPAT);


        $data = array(
            "registration_ids" => $tokens,
            "data" => array(
                "title" => "$title",
                "body" => "$message"
            ));

        $data_string = json_encode($data);

        $url = "https://fcm.googleapis.com/fcm/send";

        $headers = array
        (
            'Authorization: key=' . env('FIREBASE_API_ACCESS_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);

        $result = curl_exec($ch);

        if(json_decode($result)->success==1){
            curl_close ($ch);
            return true;
        }else{
            curl_close ($ch);
            return false;
        }


    }

}
