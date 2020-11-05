<?php

namespace App\Http\Controllers\Dashboard;


use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{


    public function index()
    {
        $settings = Setting::get();


        return view('dashboard.settings.index', compact('settings'));

    }//end of edit

    public function update(Request $request, Setting $setting)
    {


//        DB::beginTransaction();
//        $attributes=$request->all();
//        $attributes['slug']=\Illuminate\Support\Str::slug( $attributes['en']['name'], '-');
//        $page->update($attributes);
        $input = $request->all();

        $input['slug'] = \Illuminate\Support\Str::slug($input['value'], '-');

        $setting->update($input);
        return "true";


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of update

    public function updateAll(Request $request)
    {

        $request->validate([
            'Facebook' => 'required|string',
            'Email' => 'required|email|string',
            'Phone' => 'required|string',
            'Twitter' => 'required',
//            'youtube'  => 'required|string',
            'Instegram'=>'required'

                      ]);

        $data=$request->except('_token');
        foreach ($data as $key=>$value){
            $setting=Setting::where('slug',$key)->first();
            $setting->value=$value;
            $setting->save();
        }
        return redirect()->back();
    }


}//end of controller
