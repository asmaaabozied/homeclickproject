<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;
use DB;
use App\User;
use Modules\UserManagement\Entities\UserMetas;
use Modules\Geography\Entities\Geography;


// define('UserMetasKeys',['city'=>'text','contact'=>'number']); // Keys To add To User Metas Table
// define('Paginate_number',10);
// define('User_image_path','uploads/user_images/');

class UserManagementController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:read_users'])->only('index');
        $this->middleware(['permission:create_users'])->only('create');
        $this->middleware(['permission:update_users'])->only('edit');
        $this->middleware(['permission:delete_users'])->only('destroy');

    }//end of constructor

    public function index(Request $request){
        try{
            $geography = Geography::get()->pluck('name','id');
            // Search In User Metas If Serach Value Is
            $searchUserMetasIds=[];
            if(!empty($request->search)){
                $searchUserMetasIds=UserMetas::select('user_id')
                                ->join('users','user_id','users.ID')
                                ->where('type', '=', 'User')
                                ->where(function ($q) use ($request) {
                return $q->when($request->search, function ($query) use ($request) {
                    return $query->where('attr_value', 'like', '%' . $request->search . '%');
                    });
                })->pluck('user_id');
            }
            $users = User::where('type', '=', 'User')->where(function ($q) use ($request) {
                return $q->when($request->search, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })->orWhereIn('ID',$searchUserMetasIds)->latest()->paginate(Paginate_number);

            $user_ids=$users->pluck('id');
            $userMetasArr=UserMetas::whereIn('user_id',$user_ids)->get()->toArray();
            //make UserMetas As Key Value With Key User_id And Value
            //100 => array:2 [▼ "city" => "mansourah" ,"contact" => "0473622"],
            $userMetas=[];
            foreach ($userMetasArr as $userMeta) {
                if($userMeta['attr_key']=='country' ||$userMeta['attr_key']=='city')$userMeta['attr_value']= $geography[$userMeta['attr_value']];
                $userMetas[$userMeta['user_id']][$userMeta['attr_key']]=$userMeta['attr_value'];
            }
            // dd($userMetas);
            
            $subscription=[100=>["consultation","courses"],94=>["courses"],96=>["consultation"]];
            // Subscription Info Here ******************************************
            //Stile Under Proceess 
                // Name of Subscription
                // Type of Subscription (Consultation or Courses)


            //***************************************************************** */

            return view('UserManagement::manage_users.index', compact('users','userMetas','subscription'));
        } catch (QueryException $e) {
            $message = "Error".$e->message();
            return redirect()->route('dashboard.manage_users.index')->withErrors($message);

        }
        catch (Exception $e){
             $message = "Error";
             return redirect()->route('dashboard.manage_users.index')->withErrors($message);
        }
    }//end of index

    public function create(){
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        $cities = Geography::whereNotNull('parent_id')
                            ->withTranslation()
                            ->orderByTranslation('name','asc')
                            ->get()
                            ->groupBy('parent_id');

        $userMetaskey=UserMetasKeys;
        return view('UserManagement::manage_users.create' ,compact('userMetaskey','countries','cities'));
    }//end of create

    public function store(Request $request){
        $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|string|unique:users',
                'phone' => 'required|string|unique:users',
                'image' => 'image',
                'type'  => 'required|string',
                'password' => 'required|confirmed|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[!@$%^&*]/ ',
            ],
            [
                'password.regex'=> __("site.password_regex"),
            ]
        );

        $request_data = $request->except(['password', 'password_confirmation', 'permissions', 'image']);
        $request_data['password'] = bcrypt($request->password);

        if ($request->image) {
            $path=public_path(User_image_path);
            if (!file_exists($path)) {File::makeDirectory($path, 0777, true, true);}
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                ->save(public_path(User_image_path . $request->image->hashName()));
            $request_data['image'] = $request->image->hashName();
        }//end of if

        // To Make User Active
        $request_data['status']=1;
        try {
            DB::beginTransaction();
            $user = User::create($request_data);

            //$user->syncUserMetas
            $Attr_key=UserMetasKeys; // Keys To add To User Metas Table
            $userMetasAttr=[];
                foreach ($Attr_key as $key => $type) {
                    if(!empty($request->$key)){
                        $userMetasAttr[]= ['user_id'=>$user->id ,
                        'attr_key'=>$key,
                        'attr_value'=>$request->$key];
                    }
                }
            UserMetas::insert($userMetasAttr);

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->route('dashboard.manage_users.create')->withErrors( __('site.add_faild').'  <br>  /n '. $th->getMessage() );
        }
            
        
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.manage_users.index');

    }//end of store

    public function edit($user_id){
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        $cities = Geography::whereNotNull('parent_id')
                            ->withTranslation()
                            ->orderByTranslation('name','asc')
                            ->get()
                            ->groupBy('parent_id');

        $user = User::find($user_id);
        $userMetaskey=UserMetasKeys;
        $userMetas = UserMetas::where('user_id',$user_id)->pluck('attr_value','attr_key');
        return view('UserManagement::manage_users.edit', compact('user','userMetas','userMetaskey','countries','cities'));
    }//end of user

    public function update(Request $request){
        $user_id=$request->user_id;
        if(empty($user_id)){
            return back()->withErrors('User ID Not Found');
        }else{
            $user = User::find($user_id);
            $request->validate([
                'name' => 'required',
                'email' => ['required', Rule::unique('users')->ignore($user->id),],
                'phone' => ['required', Rule::unique('users')->ignore($user->id),],
                'image' => 'image',
            ]);
            $Attr_key=UserMetasKeys; // Keys To add To User Metas Table
            
            $request_data = $request->except(array_merge(['permissions', 'image'], $Attr_key));
    
            if ($request->image) {
                if ($user->image != 'default.png' && !empty($user->image)) {
                    $image_path=public_path().'/'.User_image_path.$user->image;
                    removeFileByPath($image_path);
                }//end of inner if
    
                Image::make($request->image)
                    ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
                    ->save(public_path(User_image_path . $request->image->hashName()));
                $request_data['image'] = $request->image->hashName();
    
            }//end of external if
    
            // start Transaction For update User And UserMetas
            try {

                DB::beginTransaction();
                //Update User Metas
                $sqlAttr=[];
                foreach ($Attr_key as $key => $type) {
                    if(!empty($request->$key)){
                        $sqlAttr[]='('.$user->id.',"'.$key.'","'.$request->$key.'")';
                    }
                }
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
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                return redirect()->route('dashboard.manage_users.edit',$user->id)->withErrors( __('site.update_faild').'  <br>  /n '. $th->getMessage() );
            }
        
            session()->flash('success', __('site.updated_successfully'));
            return redirect()->route('dashboard.manage_users.index');
        }
        
    }//end of update

    public function destroy($user_id){
        $user = User::find($user_id);
        if ($user->image != 'default.png') {
            $image_path=public_path().'/'.User_image_path.$user->image;
            removeFileByPath($image_path);
        }//end of if
        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.manage_users.index');

    }//end of destroy

    public function block($user_id){
        $info= User::find($user_id);
        $status=( $info->status == 0)?1:0;
        $info->status=$status;
        $info->save();
        
        //Revoke User With Status =0;
        if($status==0){
            DB::table('oauth_access_tokens')
            ->where('user_id', $user_id)
            ->delete();
        }
        session()->flash('success', __('site.updated_successfully'));
        return back();

    }//end of block
}
