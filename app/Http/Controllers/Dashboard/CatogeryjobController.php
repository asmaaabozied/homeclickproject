<?php

namespace App\Http\Controllers\Dashboard;


use App\Catogery;
use App\Catogeryjob;
use Intervention\Image\Facades\Image;

use App\Lawercase;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CatogeryjobController extends Controller
{
    public function index(Request $request)
    {

       //abort_unless(\Gate::allows('read_categories'), 403);

        $jobs =Catogeryjob::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->latest()->paginate(25);

        return view('dashboard.complains.index', compact('jobs'));

    }//end of index

    public function create()
    {
      // abort_unless(\Gate::allows('create_categories'), 403);

        return view('dashboard.complains.create');

    }//end of create

    public function store(Request $request)
    {

        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];

        }//end of for each

        $request->validate($rules);

       $catogery= Catogeryjob::create($request->except(['_token','_method']));


            session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.catogeryjobs.index');

    }//end of store

    public function edit($id)
    {
        $category=Catogeryjob::find($id);

        return view('dashboard.complains.edit',compact('category'));

    }//end of edit

    public function update(Request $request,$id )
    {
        $catogery=Catogeryjob::find($id);

        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];

        }//end of for each

        $request->validate($rules);

        $catogery->update($request->all());

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.catogeryjobs.index');

    }//end of update

    public function destroy($id )
    {

      //  abort_unless(\Gate::allows('category_delete'), 403);
     $catogery=Catogeryjob::find($id);

        $catogery->translations()->delete();
        $catogery->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.catogeryjobs.index');

    }//end of destroy



    public function change_status($id){

        $info= Catogeryjob::find($id);
        $status=( $info->status == 0)?1:0;
        $info->status=$status;
        $info->save();
        session()->flash('success', __('site.updated_successfully'));
        return back();


    }//end of change



}//end of controller
