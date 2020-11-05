<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Modules\UserManagement\Entities\UserMetas;
use App\User;
use Validator;
use Hash;
use Lang;
use DB;
use Auth;
use Carbon\Carbon;
use LaravelLocalization;

class RegisterController extends Controller
{
    public function __construct(){
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }

    //This Function User For Register Main Users With Type Consaltant , User(Paied Or Not)
    public function registerPhone(Request $request){
        // Frist Time User Dend His Phone For Register
        //1- User Is already registered If Password Reseting And VCode exsist And Phone Number Is Exsit
        //2- User Verified Before But He Need Update His Data
        //3- User Not Verified And His status False
            $rules = [
                // 'phone'    => 'unique:users|required|min:9',
                'phone'    => 'required|min:9',
                'user_type'=> 'required'
            ];
            $customMessages = [
                'required' => __('validation.attributes.required'),
                'unique' => __('validation.attributes.unique'),
            ];
            $input = $request->only('phone','user_type');
            $validator = Validator::make($input, $rules, $customMessages);

            $phone    = $request->phone;// like'+201065353173'
            $user_type = $request->user_type;
            $v_code=mt_rand(1000, 9999);
            if ($validator->fails()) {//$validator->errors()->all()
                return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())],422);
            }
            // check User Type
            if (! in_array($user_type ,['User','Admin']) ) {
                return response()->json(['status' => 422, 'message' =>__('site.messages.userTypeInvalid')],422);
            }

            // check if User Is Exist Before [true ,  message , status]
            $result=$this->checkUserPhoneExist($phone);
            // dd($result[2]);
            if($result[0]){
                // there are Confilect -> user Exist
                return response()->json(['status' => 409, 'message' =>$result[1]],409);
            }
            if(!empty($result[2])){
                /////////////////////////////////////////////////////////////////////////////////////////////\\\
                // if true he need to complete his data
                // if false user will get message then ned verification
                // if ($result[2]=='false'){
                //     //send message to his phone number and update hi s vCode
                //     $userData = User::where('phone' , $phone)->first();
                //     // dd($userData->updated_at);
                //     //check if User  spend more than 2 minutes
                //     if (\Carbon\Carbon::parse($userData->updated_at)->addMinutes(2)->isFuture()){
                //         return response()->json(['status' => 422, 'message' =>__('site.messages.user_wait2minutes')],422);
                //     }else{
                //         $message=PhoneVerificationMessage.$v_code;
                //         //$sendMsg=sendMessage($message, $phone);
                //         $sendMsg[0]=true;
                //         if($sendMsg[0]){
                //             $userData->update(['verification_code' =>$v_code]);
                //             return response()->json(['status' => 200, 'message' =>$result[1] , 'verified'=>$result[2] ,'v_code'=>$v_code]);

                //         }
                //         else{
                //             return response()->json(['status' => 417, 'message' =>$sendMsg[1].','.__('site.messages.tryAgain')],417);
                //         }
                //     }

                //     // return response()->json(['status' => 200, 'message' =>"send messane"]);
                // }
                // return response()->json(['status' => 200, 'message' =>$result[1] , 'verified'=>$result[2]]);
                /////////////////////////////////////////////////////////////////////////////////////////////\\\

                $userData = User::where('phone' , $phone)->first();
                if ($result[2]=='true'){
                    //Update User Dtate To Phone Not Verified So We Can Start Over This Cycle
                    $userData->status = 0;
                    $userData->phone_verified_at = null;
                    $userData->save();
                }
                //check if User  spend more than 2 minutes
                if (\Carbon\Carbon::parse($userData->updated_at)->addMinutes(2)->isFuture()){
                    return response()->json(['status' => 422, 'message' =>__('site.messages.user_wait2minutes')],422);
                }else{
                    $message=PhoneVerificationMessage.$v_code;
                    //$sendMsg=sendMessage($message, $phone);
                    $sendMsg[0]=true;
                    if($sendMsg[0]){
                        $userData->update(['verification_code' =>$v_code]);
                        return response()->json(['status' => 200, 'message' =>$result[1] , 'verified'=>$result[2] ,'v_code'=>$v_code]);
                    }
                    else{
                        return response()->json(['status' => 417, 'message' =>$sendMsg[1].','.__('site.messages.tryAgain')],417);
                    }
                }

            }
            // i will send him message and then save data in users (phone and type)
            // if Message Send .. add user Data
            // Here Send message To user On His Mail - address

            $message=PhoneVerificationMessage.$v_code;
            //$sendMsg=sendMessage($message, $phone);
            $sendMsg[0]=true;
            if($sendMsg[0]){
                // $user = User::create(['name' => $name,
                //                     'phone' => $phone,'password' => Hash::make($password),
                //                     'email' => $email,'verification_code' => $v_code,
                //                     'type'=> $user_type]
                //                 );
                $user = User::create(['phone' => $phone,'verification_code' => $v_code,'type'=> $user_type]);

                if(!empty($phone_code)){
                    $sqlAttrStr='('.$user->id.',"phone_code","'.$phone_code.'")';
                    $sql = 'INSERT INTO user_metas (user_id, attr_key, attr_value) VALUES '.$sqlAttrStr.
                        'ON DUPLICATE KEY UPDATE attr_value=VALUES(attr_value)';
                    DB::statement($sql);
                }
                return response()->json(['status' => 200, 'message' =>__('site.messages.user_codeSended'),'v_code'=>$v_code]);
            }
            else{
                return response()->json(['status' => 417, 'message' =>$sendMsg[1].','.__('site.messages.tryAgain')],417);
                //return response()->json(['status' => 404, 'message' => 'Error While Sending Message To User phone , Please Try Again Later!']);
            }

    }

    private function checkUserPhoneExist($phone){
        // *check if New user Has Mail Or Has Phone Exsist Before
        // *check if New User Registerd before And Active Or Not Active
        if (!empty($phone)) {
            // Get users has This info
            $existUser = User::where('phone' , $phone)->get();
            if (count($existUser)>0) {

                $phone_verified_at=$existUser[0]->phone_verified_at;
                $password=$existUser[0]->password;
                if(!empty($password)){
                    return [true ,__('site.messages.user_registedBefore')];
                }
                $status =(!empty($phone_verified_at))?__('site.messages.user_phoneVerified'):__('site.messages.user_phoneNotVerified');
                return [false ,$status , (!empty($phone_verified_at))?'true':'false' ];
            }
            else {return [false ,__('site.messages.user_notExist')];}
        }
        else {return [false ,__('site.messages.invalidUserId')];}
    }

    private function checkUserExist($mail, $phone){
        // *check if New user Has Mail Or Has Phone Exsist Before
        // *check if New User Registerd before And Active Or Not Active
        //* check if New User Has Exist Phone Or email
        if (!empty($mail) &&!empty($phone)) {
            // Get users has This info
            $existUser = User::where('email' , $mail)->where('phone' , $phone)->get();
            if (count($existUser)>0) {
                $phone_verified_at=$existUser[0]->phone_verified_at;
                $status =(!empty($phone_verified_at))?__('site.messages.user_phoneVerified'):__('site.messages.user_phoneNotVerified');
                return [true ,__('site.messages.user_existBefore').' , '.$status ,
                (!empty($phone_verified_at))?true:false];
            }
            else {return [false ,__('site.messages.user_notExist')];}
        }
        else {return [false ,__('site.messages.invalidUserId')];}
    }

    public function verify_phone(Request $request){
        // This Function verify User Accont With His Phone And Verification Code
        $rules = [
            'code' => 'required|min:4',
            'phone'=> 'required',
        ];
        $input     = $request->only('code','phone');

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];
        $validator = Validator::make($input, $rules, $customMessages);
        $code = $request->code;
        $phone = $request->phone;
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())],422);
        }
        // check if code And Phone Verified Before
        $User = User::where([['verification_code','=',$code],['phone','=',$phone]])->get();
        if(!empty($User[0]->phone_verified_at)){
            return response()->json(['status' => 409, 'message' =>__('site.messages.user_existBefore')],409);
        }
        else {
            $updateUser = User::where([['verification_code',$code],['phone',$phone]])
                        ->update(['phone_verified_at' =>Carbon::now() , 'status' =>1]);
            if( $updateUser==0){
                //update Failed Incorrect Data
                return response()->json(['status' => 422, 'message' =>__('site.messages.opertaion_faild').' '.__('site.messages.user_codeInvalid')],422);
            }
            else{
                // $updateUser==1 => update Done Sussesfully
                return response()->json(['status' => 200, 'message' =>__('site.messages.opertaion_success')]);
            }
        }
    }





    public function register_data(Request $request){
        $rules = [
            'name' => 'required',
            'email'    => 'max:254|unique:users|email|required',
            'phone'    => 'required|min:9',
            // 'phone_code'    => 'required',
//            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[!-\/:-@\[-`{-~]/',
            'password'=>'required',
            // 'user_type'=> 'required',
            'v_code'=> 'required',
            'birthdate' => ['before:13 years ago'], // rules
            'before' => 'You must be at least 13 years old' // messages
        ];
        $input = $request->only('name','email','phone','password','v_code','birthdate');
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];
        $validator = Validator::make($input, $rules, $customMessages);
        $phone = $request->phone;
        $v_code = $request->v_code;

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())],422);
        }
        // check if code And Phone Verified Before
        $user = User::where('phone','=',$phone)->first();
        // check User
        if (empty($user)) {
            return response()->json(['status' => 422, 'message' =>__('site.messages.user_notExist')],422);
        }
        // // check User password
        if(!empty($user->password)){
            return response()->json(['status' => 409, 'message' =>__('site.messages.user_registedBefore')],409);
        }

        if ($user->verification_code!=$v_code) {
            return response()->json(['status' => 422, 'message' =>__('site.messages.user_codeInvalid')],422);
        }
        // check User Type
        if (! in_array($user->type ,['User','Admin']) ) {
            return response()->json(['status' => 422, 'message' =>__('site.messages.userTypeInvalid')],422);
        }
        // check User Phone
        if(empty($user->phone_verified_at)){
            return response()->json(['status' => 422, 'message' =>__('site.messages.user_phoneNotVerified')],422);
        }
        if(!empty($user->phone_verified_at) && $user->status==0){
            return response()->json(['status'=>402 , 'message'=>__('site.messages.user_block')],402);
        }

        // we can now check if code is valid Then Its okay To Reset
        // add usermetas optinal values
        // $birthdate = $request->birthdate;
        // $position = $request->position;
        // $city = $request->city;
        // $image = $request->image;
        // $firebase_token = $request->firebase_token;

        //Update User Metas
        $Attr_key=($user->type=='User')?UserMetasKeys:EmpMetasKeys;

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
            $user->image = $request->image->hashName();
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
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            DB::commit();

            if(!Auth::attempt(['phone' =>$user->phone , 'password'=>$request->password ])){
                return response()->json(['status'=>401 , 'message'=>__('site.messages.user_loginInvalid'),401]);
            }
            // $user =Auth::user();
            $objToken = Auth::user()->createToken('authToken');
            $accessToken =$objToken->accessToken;
            $expired_at = $objToken->token->expires_at->diffInDays(Carbon::now());

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
                'member_since'=>$memberSince,
                'access_token'=>$accessToken,
                'expired_days'=>$expired_at,
                "image"=>$user->getImagePathAttribute(),
                'user_metas'=>$userMetas,
            ];
            return response()->json(['status'=>200 ,'user'=>$userData , 'message' =>__('site.messages.opertaion_success')]);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status'=>417 ,'message'=> __('site.update_faild').'  <br>  /n '. $th->getMessage()]);
        }
    }
}
