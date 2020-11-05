<?php

namespace App\Http\Controllers\Dashboard;

use App\Lawer;
use App\Subscribe;
use App\Type;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

use App\Lawercase;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{

    public function index(Request $request)
    {


            $subs = Subscribe::latest()->paginate(25);


            return view('dashboard.subscriptions.index', compact('subs'));


    }//end of index

    public function create()
    {
        // abort_unless(\Gate::allows('create_categories'), 403);


        return view('dashboard.subscriptions.create');

    }//end of create

    public function store(Request $request)
    {

        $rules = [
//            'image'=>'required',
//            'number'=>'required',
//            'user_id'=>'required',
        ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];

        }//end of for each

        $request->validate($rules);

        $sub = Subscribe::create($request->except(['_token', '_method']));


        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.subscriptions.index');

    }//end of store

    public function edit($id)
    {

        $subscribe = Subscribe::find($id);


        return view('dashboard.subscriptions.edit', compact('subscribe'));

    }//end of edit

    public function update(Request $request, $id)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];


        }//end of for each

        $request->validate($rules);

        $sub = Subscribe::find($id);

        $sub->update($request->all());


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.subscriptions.index');

    }//end of update

    public function destroy($id)
    {
        //  abort_unless(\Gate::allows('category_delete'), 403);

        $sub = Subscribe::find($id);
        $sub->translations()->delete();
        $sub->delete();
        session()->flash('success', __('site.deleted_successfully'));

        return back();

    }//end of destroy


}//end of controller
