<?php

namespace Modules\Geography\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

use Modules\Geography\Entities\Geography;

class CityController extends Controller
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
            $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');

            $cities = Geography::whereNotNull('parent_id')->where(function ($q) use ($request) {
                return $q->when($request->search, function ($query) use ($request) {
                    return $query->whereTranslationLike('name','%'. $request->search .'%')
                        // ->orWhere('description', 'like', '%' . $request->search . '%')
                        ;
                });
            })->orderBy('parent_id', 'ASC')
            ->orderByTranslation('name', 'ASC')
            ->paginate(Paginate_number);

            // dd($cities);
            return view('geography::city.index', compact('cities','countries'));
        } catch (QueryException $e) {
            $message = "Error".$e->message();
            return redirect()->route('dashboard.cities.index')->withErrors($message);

        }
        catch (Exception $e){
             $message = "Error".$e->message();
             return redirect()->route('dashboard.cities.index')->withErrors($message);
        }
    } // end of index

    public function create(){
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        return view('geography::city.create', compact('countries'));
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
        return redirect()->route('dashboard.cities.index');

    }//end of store

    public function edit($id){
        $city = Geography::whereNotNull('parent_id')->find($id);
        if(empty($city)){
            return redirect()->route('dashboard.cities.index')
                                ->withErrors( __('site.update_faild').'  <br>   '. __('site.message.user_notExist') );
        }
        else{
            $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
            return view('geography::city.edit', compact('city' ,'countries'));
        }
    }//end of user

    public function update(Request $request,$id){
        // dd($request,$id);
        $cities = Geography::find($id)->whereNotNull('parent_id')->first();
        if(empty($cities)){
            return redirect()->route('dashboard.cities.edit',$id)
                                ->withErrors( __('site.update_faild').'  <br>   '. __('site.message.user_notExist') );
        }
        else{
            $rules = [];
            foreach (config('translatable.locales') as $locale) {
                $rules += [$locale . '.name' => ['required']];
            }//end of for each
            $request->validate($rules);
            $cities->update($request->all());

            session()->flash('success', __('site.updated_successfully'));
            return redirect()->route('dashboard.cities.index');
        }


    }//end of update
    public function destroy($id){
        $geography = Geography::whereNotNull('parent_id')->find($id);
        $geography->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.cities.index');

    }//end of destroy

}
