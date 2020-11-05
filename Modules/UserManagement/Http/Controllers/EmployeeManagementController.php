<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
// use Illuminate\Support\Facades\File; 
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;
use DB;
use App\User;
use Modules\UserManagement\Entities\UserMetas;
use Modules\Geography\Entities\Geography;


// define('EmpMetasKeys',['job_title'=>'text','city'=>'text', 'consultant_cost'=>'number', 'quilification'=>'file' ,'quilification_brief'=>'textarea']); // Keys To add To User Metas Table
// define('Paginate_number',10);
// define('User_image_path','uploads/user_images/');
// define('Consultant_path','uploads/consultants/');
class EmployeeManagmentController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:read_employees'])->only('index');
        $this->middleware(['permission:create_employees'])->only('create');
        $this->middleware(['permission:update_employees'])->only('edit');
        $this->middleware(['permission:delete_employees'])->only('destroy');

    }//end of constructor

    public function index(Request $request){
        try{
            $geography = Geography::get()->pluck('name','id');
            // Search In User Metas If Serach Value Is
            $searchUserMetasIds=[];
            if(!empty($request->search)){
                $searchUserMetasIds=UserMetas::select('user_id')
                                ->join('users','user_id','users.ID')
                                ->where('type', '=', 'Consultant')
                                ->where(function ($q) use ($request) {
                return $q->when($request->search, function ($query) use ($request) {
                    return $query->where('attr_value', 'like', '%' . $request->search . '%');
                    });
                })->pluck('user_id');
                //  dd($searchUserMetasIds);
            }
            $users = User::where('type', '=', 'Consultant')->where(function ($q) use ($request) {
                return $q->when($request->search, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })->orWhereIn('ID',$searchUserMetasIds)->latest()->paginate(Paginate_number);

            //asset('public/'.Consultant_path. $this->image);
            $user_ids=$users->pluck('id');
            $userMetasArr=UserMetas::whereIn('user_id',$user_ids)->get()->toArray();
            //make UserMetas As Key Value With Key User_id And Value
            //100 => array:2 [▼ "city" => "mansourah" ,"contact" => "0473622"],
            $userMetas=[];
            foreach ($userMetasArr as $userMeta) {
                if($userMeta['attr_key']=='quilification')$userMeta['attr_value']= asset('public/'.Consultant_path.$userMeta['attr_value']);
                if($userMeta['attr_key']=='country' ||$userMeta['attr_key']=='city')$userMeta['attr_value']= $geography[$userMeta['attr_value']];
                $userMetas[$userMeta['user_id']][$userMeta['attr_key']]=$userMeta['attr_value'];
            }
            // dd($userMetas);
            return view('UserManagement::manage_employees.index', compact('users','userMetas'));
        } catch (QueryException $e) {
            $message = "Error".$e->message();
            return redirect()->route('dashboard.manage_employees.index')->withErrors($message);
        }
        catch (Exception $e){
             $message = "Error";
             return redirect()->route('dashboard.manage_employees.index')->withErrors($message);
        }
    }//end of index

    public function create(){
        $userMetaskey=EmpMetasKeys;
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        $cities = Geography::whereNotNull('parent_id')
                            ->withTranslation()
                            ->orderByTranslation('name','asc')
                            ->get()
                            ->groupBy('parent_id');

        return view('UserManagement::manage_employees.create' ,compact('userMetaskey','countries','cities'));
    }//end of create

    public function store(Request $request){
        $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|string|unique:users',
                'phone' => 'required|string|unique:users',
                'image' => 'image',
                'type'  => 'required|string',
                'password' => 'required|confirmed|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[!@$%^&*]/ ',
                'quilification' => 'mimes:pdf,csv,doc,docx|max:2048',

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
        }//end of if Upload Image

        if ($request->quilification) {
            $fileName = time().'.'.$request->quilification->extension();  
            $request->quilification->move(public_path(Consultant_path), $fileName);
            $request->quilification = $fileName ;
        }//end of if upload File

        // To Make User Active
        $request_data['status']=1;
        try {
            DB::beginTransaction();
            $user = User::create($request_data);

            //$user->syncUserMetas
            $Attr_key=EmpMetasKeys; // Keys To add To User Metas Table
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
            return redirect()->route('dashboard.manage_employees.create')->withErrors( __('site.add_faild').'  <br>  /n '. $th->getMessage() );
        }
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.manage_employees.index');

    }//end of store

    public function edit($user_id){
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        $cities = Geography::whereNotNull('parent_id')
                            ->withTranslation()
                            ->orderByTranslation('name','asc')
                            ->get()
                            ->groupBy('parent_id');
                            
        $user = User::find($user_id);
        $userMetaskey=EmpMetasKeys;
        $userMetas = UserMetas::where('user_id',$user_id)->pluck('attr_value','attr_key');
        // dd($userMetas);
        // make file name it Url
        if(!empty($userMetas['quilification']))$userMetas['quilification']= asset('public/'.Consultant_path.$userMetas['quilification']);
        // dd($user ,$userMetaskey , $userMetas);
        return view('UserManagement::manage_employees.edit', compact('user','userMetas','userMetaskey','countries','cities'));
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
                'quilification' => 'mimes:pdf,csv,doc,docx|max:2048',
            ]);
            $Attr_key=EmpMetasKeys; // Keys To add To User Metas Table
            
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
            
            if ($request->quilification) {
                //remove old file
                $userMetas=UserMetas::where('user_id',$user_id)->where('attr_key','quilification')->pluck( 'attr_value','attr_key');
                if(!empty($userMetas['quilification'])){
                    $file_path=public_path(Consultant_path.$userMetas['quilification']);
                    removeFileByPath($file_path);
                }

                $fileName = time().'.'.$request->quilification->extension();  
                $request->quilification->move(public_path(Consultant_path), $fileName);
                $request->quilification = $fileName ;
            }//end of if upload File
    
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
                return redirect()->route('dashboard.manage_employees.edit',$user->id)->withErrors( __('site.update_faild').'  <br>  /n '. $th->getMessage() );
            }
        
            session()->flash('success', __('site.updated_successfully'));
            return redirect()->route('dashboard.manage_employees.index');
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
        return redirect()->route('dashboard.manage_employees.index');

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
