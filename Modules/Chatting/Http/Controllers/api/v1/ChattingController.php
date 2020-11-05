<?php

namespace Modules\Chatting\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File; 
use Modules\Chatting\Entities\Chatting;
use Illuminate\Validation\Rule;
use Validator;
// use DB;
// use App\User;
class ChattingController extends Controller
{
    // public function __construct()
    // {
    //     $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
    //     LaravelLocalization::setLocale($local);
    // }
    public function upload_chatting_image(Request $request){
        $rules=[
            // 'receiver_id' => 'required',
            'image' => 'required|image',
            // 'type'  => 'required|string',
            // 'quilification' => 'mimes:pdf,csv,doc,docx|max:2048',
        ];
        // $Attr_key=($user->type=='User')?UserMetasKeys:EmpMetasKeys;
        $input=$request->only('image');
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];
        $validator = Validator::make($input, $rules, $customMessages);
        if ($validator->fails()) {//$validator->errors()->all()
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())]);
        }

        if ($request->image) {
            $path=public_path(Chatting_image_path);
            $path_thumb=public_path(Chatting_image_path.'thumb/');

            if (!file_exists($path)) {File::makeDirectory($path, 0777, true, true);}
            if (!file_exists($path_thumb)) {File::makeDirectory($path_thumb, 0777, true, true);}

            $image_name=$request->image->hashName();
            $image_path=$path.$image_name;
            Image::make($request->image)
                ->resize(800, null, function ($constraint) {$constraint->aspectRatio();})
                ->save($image_path);
            $request_data['image_path'] =asset('public/'.Chatting_image_path . $image_name);
            
            
            $image_thumb_path=$path.'thumb/'.$image_name;
            Image::make($request->image)
                ->resize(150, null, function ($constraint) {$constraint->aspectRatio();})
                ->save($image_thumb_path);
            $request_data['image_thumb'] =asset('public/'.Chatting_image_path .'thumb/'. $image_name);
            

        }//end of if Upload Image

        // if ($request->quilification) {
        //     $fileName = time().'.'.$request->quilification->extension();  
        //     $request->quilification->move(public_path(Consultant_path), $fileName);
        //     $request->quilification = $fileName ;
        // }//end of if upload File

        //save messages;
        // $chatting = Chatting::create([
        //     'sender_id'=>$request->user()->id,
        //     'message'   =>  $image_name,
        //     'type'  =>'image'
        // ]);
        return response()->json(['status'=>200 ,'data'=>$request_data , 'message' =>__('site.messages.opertaion_success')]);
            // return view('chatting::index');
    }
    
    public function save_message(Request $request)
    {
        //'sender_id' ,'receiver_id' ,	'message', 'type'
        $rules=[
            'receiver_id' => 'required',
            'message' => 'required',
            'type' => 'required',
        ];
        $sender_id=auth()->user()->id;
        $input=$request->only('receiver_id','message','type');
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];
        $validator = Validator::make($input, $rules, $customMessages);
        if ($validator->fails()) {//$validator->errors()->all()
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())]);
        }
        $request_data = $request->only('receiver_id','message','type');
        $request_data['sender_id'] =$sender_id;
        $chatting = Chatting::create($request_data);
        return response()->json(['status' => 200, 'message' =>__('site.messages.opertaion_success')]);
    }
}
