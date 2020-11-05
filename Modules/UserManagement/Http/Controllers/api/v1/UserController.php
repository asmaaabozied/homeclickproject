<?php

namespace Modules\UserManagement\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Modules\UserManagement\Entities\UserMetas;
use App\User;
use Lang;
use DB;
use Carbon\Carbon;
use Validator;
use LaravelLocalization;

class UserController extends Controller
{
    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }


    public function show(Request $request){
        try {
            // $userData=__('site.no_data_found');
            $user= $request->user();

            if(empty($user)){
                return response()->json(['status'=>401 , 'message'=>__('site.messages.user_loginInvalid')]);
            }
            else{
                $userCreatesAt=Carbon::parse($user->created_at);
                $memberSince=  $userCreatesAt->diffForHumans(Carbon::now());
                $userMetas = UserMetas::where('user_id',$user->id)->pluck('attr_value','attr_key');

                //fill UserMetas With Key Not avalibale
                // from Array UserMetasKeys , EmpMetasKeys
                $array_user_metas=($user->type=='User')?UserMetasKeys:EmpMetasKeys;
                foreach ($array_user_metas as $key => $type) {
                    if(empty($userMetas[$key]))$userMetas[$key]='';
                }
                // dd($userMetas);

                ///////////////////////////////////////////////////
                // Just Will Update Information After Do Its Modules
                //  $courses; consultations; package
                ////////////////////////////////////////////////////

                $userData=[
                    "id"=> $user->id,
                    "name"=>$user->name,
                    "email"=>$user->email,
                    "phone"=>$user->phone,
                    // "type"=>$user->type,
                    "image"=>$user->getImagePathAttribute(),
                    'member_since'=>$memberSince,
                    'user_metas'=>$userMetas,
                ];

                return response()->json(['status'=>200 ,'user'=>$userData , 'message' =>__('site.messages.opertaion_success')]);
            }
        } catch (\Throwable $th) {
           return response()->json(['status' => 404, 'message' =>__('site.messages.invalidToken')]);
       }
    }

    public function update(Request $request ){

        $user= $request->user();
        if(empty( $user->id)){
            return response()->json(['status'=>401 , 'message'=>__('site.messages.user_loginInvalid')]);
        }
        else if (! in_array($user->type ,['User','Consultant']) ) {
            return response()->json(['status' => 422, 'message' =>__('site.messages.userTypeInvalid')]);
        }
        else{
            $user = User::find($user->id);
            $rules=[
                'name' => 'string',
                'email' => [ Rule::unique('users')->ignore($user->id),],
                // 'phone' => [ Rule::unique('users')->ignore($user->id),],
                'image' => 'image',
            ];

            $Attr_key=($user->type=='User')?UserMetasKeys:EmpMetasKeys;

            // $input=$request->only('name','email','phone','image');
            $input=$request->only('name','email','image');
            $customMessages = [
                'required' => __('validation.attributes.required'),
                'unique' => __('validation.attributes.unique'),
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {//$validator->errors()->all()
                return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())]);
            }

            // $request_data = $request->except(array_merge(['permissions', 'image'], $Attr_key));
            $request_data  = $request->only('name','email','phone','image');

            //Update User Metas
            $sqlAttr=[];
            foreach ($Attr_key as $key => $type) {
                if(!empty($request->$key)){
                    $sqlAttr[]='('.$user->id.',"'.$key.'","'.$request->$key.'")';
                }
            }
            if(empty($request_data) && empty($sqlAttr) ){
                return response()->json(['status' => 422, 'message' =>__('site.messages.user_dataNotExist')]);
            }

            if ($request->image) {
                if ($user->image != 'default.png' && !empty($user->image)) {
                    $image_path=public_path().User_image_path.$user->image;
                    $this->removeFile($image_path);
                }//end of inner if
                Image::make($request->image)
                    ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                    ->save(public_path(User_image_path . $request->image->hashName()));
                $request_data['image'] = $request->image->hashName();
            }//end of external if

            // start Transaction For update User And UserMetas
            try {
                DB::beginTransaction();
                if(count($sqlAttr)>0){
                    // DB::table('user_metas')->insert($sqlArr);
                    $sqlAttrStr=implode(",",$sqlAttr);
                    $sql = 'INSERT INTO user_metas (user_id, attr_key, attr_value) VALUES '.$sqlAttrStr.
                            'ON DUPLICATE KEY UPDATE attr_value=VALUES(attr_value)';
                    DB::statement($sql);
                }
                //Update User Data
                $user->update($request_data);
                DB::commit();

                $userCreatesAt=Carbon::parse($user->created_at);
                $memberSince=  $userCreatesAt->diffForHumans(Carbon::now());
                $userMetas = UserMetas::where('user_id',$user->id)->pluck('attr_value','attr_key');
                //fill UserMetas With Key Not avalibale
                // from Array UserMetasKeys , EmpMetasKeys
                $array_user_metas=($user->type=='User')?UserMetasKeys:EmpMetasKeys;
                foreach ($array_user_metas as $key => $type) {
                    if(empty($userMetas[$key]))$userMetas[$key]='';
                }
                if(empty($userMetas['phone_code']))$userMetas['phone_code']="";

                $userData=[
                    "id"=> $user->id,
                    "name"=>$user->name,
                    "email"=>$user->email,
                    "phone"=>$user->phone,
                    // "type"=>$user->type,
                    "image"=>$user->getImagePathAttribute(),
                    'member_since'=>$memberSince,
                    'user_metas'=>$userMetas,
                ];
                return response()->json(['status'=>200 ,'user'=>$userData , 'message' =>__('site.messages.opertaion_success')]);

            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['status'=>417 ,'message'=> __('site.update_faild').'  <br>  /n '. $th->getMessage()]);
            }
        }

    }

    private function removeFile($path){
        try {
            //Storage::disk('public')->delete($path);//$path ='/user_images/' . $user->image
            unlink($path);
        } catch ( \Throwable $e ) {error_log("try to delete : ".$e);}
    }
}
