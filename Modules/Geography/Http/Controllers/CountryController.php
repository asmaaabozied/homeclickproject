<?php

namespace Modules\Geography\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

use Modules\Geography\Entities\Geography;

class CountryController extends Controller
{
    public function __construct(){
        //create read update delete
        $this->middleware(['permission:read_geographies'])->only('index');
        $this->middleware(['permission:create_geographies'])->only('create');
        $this->middleware(['permission:update_geographies'])->only('edit');
        $this->middleware(['permission:delete_geographies'])->only('destroy');

    }//end of constructor

    public function index(Request $request){
        try{
            $countries = Geography::whereNull('parent_id')->where(function ($q) use ($request) {
                return $q->when($request->search, function ($query) use ($request) {
                    return $query->whereTranslationLike('name','%' . $request->search .'%')
                        // ->orWhere('description', 'like', '%' . $request->search . '%')
                        ;
                });
            })->latest()->paginate(Paginate_number);
            // dd($countries);
            return view('geography::country.index', compact('countries'));
        } catch (QueryException $e) {
            $message = "Error".$e->message();
            return redirect()->route('dashboard.countries.index')->withErrors($message);

        }
        catch (Exception $e){
             $message = "Error".$e->message();
             return redirect()->route('dashboard.countries.index')->withErrors($message);
        }
    } // end of index

    public function create(){
        return view('geography::country.create');
    }//end of create

    public function store(Request $request){
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];

        }//end of for each
        $request->validate($rules);
        $request_data = $request->except(['description']);

        $geography = Geography::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.countries.index');

    }//end of store

    public function edit($id){
        $country = Geography::whereNull('parent_id')->find($id);
        if(empty($country)){
            return redirect()->route('dashboard.countries.index')
                                ->withErrors( __('site.update_faild').'  <br>   '. __('site.message.user_notExist') );
        }
        else{
            return view('geography::country.edit', compact('country'));
        }
    }//end of user

    public function update(Request $request,$id){
        $countries = Geography::find($id)->whereNull('parent_id')->first();
        if(empty($countries)){
            return redirect()->route('dashboard.countries.edit',$id)
                                ->withErrors( __('site.update_faild').'  <br>   '. __('site.message.user_notExist') );
        }
        else{
            $rules = [];
            foreach (config('translatable.locales') as $locale) {
                $rules += [$locale . '.name' => ['required']];
            }//end of for each
            $request->validate($rules);
            $countries->update($request->all());

            session()->flash('success', __('site.updated_successfully'));
            return redirect()->route('dashboard.countries.index');
        }


    }//end of update

    public function destroy($id){
        $geography = Geography::whereNull('parent_id')->find($id);
        $geography->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.countries.index');

    }//end of destroy

}
