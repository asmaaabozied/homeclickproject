<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Role;
use Illuminate\Support\Str;
use Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;

// define('Paginate_number',10);
// define('User_image_path','uploads/user_images/');

class UserController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:read_admins'])->only('index');
        $this->middleware(['permission:create_admins'])->only('create');
        $this->middleware(['permission:update_admins'])->only('edit');
        $this->middleware(['permission:delete_admins'])->only('destroy');

    }//end of constructor

    public function index(Request $request){
        try{
            ///$users = User::whereRoleIs('admin')->where(function ($q) use ($request) {
            $users = User::where('type', '=', 'admin')->where(function ($q) use ($request) {

                return $q->when($request->search, function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })->latest()->paginate(Paginate_number);
            //dd($users);
            return view('dashboard.users.index', compact('users'));
        } catch (QueryException $e) {
            $message = "Error";
            return response()->json($message, 500);
        }
        catch (Exception $e){
             $message = "Error";
            return response()->json($message, 500);
        }
    }//end of index
    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    public function create(){
        $roles = Role::all();
        return view('dashboard.users.create', compact('roles'));
    }//end of create

    public function store(Request $request){
        $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|string|unique:users',
                'phone' => 'required|string|unique:users',
                'image' => 'image',
                'type'  => 'required|string',
                  'password'=>'required',
//                'password' => 'required|confirmed|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[!@$%^&*]/ ',
                'roles' =>'required'
            ],
            [
//                'password.regex'=> __("site.password_regex"),
                'roles.required'=>__("site.roles_required"),
                ]
        );

//
//        $user=User::create($request->except(['_token','_method','password', 'password_confirmation']));

        $request_data = $request->except(['password', 'password_confirmation', 'permissions','image']);


        $user['password'] = bcrypt($request->password);

//        if ($request->image) {
//            $path=public_path(User_image_path);
//            if (!file_exists($path)) {File::makeDirectory($path, 0777, true, true);}
//            Image::make($request->image)
//                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
//                ->save(public_path(User_image_path . $request->image->hashName()));
//            $request_data['image'] = $request->image->hashName();
//        }//end of if


        // To Make User Active
        $request_data['status']=1;

        $user = User::create($request_data);
        // $user->attachRole('admin');
        $user->syncRoles($request->roles);



        if($request->file('image')) {

            $images = $request->file('image');

            $img = "";
            $img = $this->str_random(4) . $images->getClientOriginalName();
            $originname = time() . '.' . $images->getClientOriginalName();
            $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
            $filename = $images->hashName();
            $extention = pathinfo($originname, PATHINFO_EXTENSION);
            $img = $filename;

            $destintion = 'uploads';
            $images->move($destintion, $img);
            $image = new \App\Image();
            $image->image = $img;
            $image->imageable_id = $user->id;
            $image->imageable_type ='App\User';
            $image->save();


        }

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');

    }//end of store

    public function edit(User $user){
        $roles = Role::all();
        return view('dashboard.users.edit', compact('user','roles'));
    }//end of user

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'phone' => ['required', Rule::unique('users')->ignore($user->id),],
            'image' => 'image',
        ]);

        $request_data = $request->except(['permissions', 'image']);

//        if ($request->image) {
//            if ($user->image != 'default.png' && !empty($user->image)) {
//                $image_path=public_path().'/uploads/user_images/'.$user->image;
//                $this->removeFile($image_path);
//            }//end of inner if
//
//            Image::make($request->image)
//                ->resize(300, null, function ($constraint) {$constraint->aspectRatio();})
//                ->save(public_path(User_image_path . $request->image->hashName()));
//            $request_data['image'] = $request->image->hashName();
//
//        }//end of external if

        $user->update($request_data);

        if($request->file('image')) {

            $images = $request->file('image');

            $img = "";
            $img = $this->str_random(4) . $images->getClientOriginalName();
            $originname = time() . '.' . $images->getClientOriginalName();
            $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
            $filename = $images->hashName();
            $extention = pathinfo($originname, PATHINFO_EXTENSION);
            $img = $filename;


            $destintion = 'uploads';
            $images->move($destintion, $img);
            $image = \App\Image::where('imageable_id',$user->id);
            $image->update([
                'imageable_id'=>$user->id,
                'image'=>$img,
                'imageable_type'=>'App\User',

            ]);


        }

        if(isset($request->roles))$user->syncRoles($request->roles);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');

    }//end of update

    public function destroy(User $user){
        if ($user->image != 'default.png') {
            $image_path=public_path().'/uploads/user_images/'.$user->image;
            $this->removeFile($image_path);
        }//end of if
        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
//        return redirect()->route('dashboard.users.index');

    }//end of destroy

    public function block($user_id)
    {
        $info= User::find($user_id);
        $status=( $info->status == 0)?1:0;
        $info->status=$status;
        $info->save();
        session()->flash('success', __('site.updated_successfully'));
        return back();

        //Revoke User With Status =0;
        if($status==0){
            DB::table('oauth_access_tokens')
            ->where('user_id', $user_id)
            ->delete();
        }

    }//end of update


    private function removeFile($path)
    {
        try {
            //Storage::disk('public')->delete($path);//$path ='/user_images/' . $user->image
            unlink($path);
        } catch ( \Throwable $e ) {error_log("try to delete : ".$e);}
    }

}//end of controller
