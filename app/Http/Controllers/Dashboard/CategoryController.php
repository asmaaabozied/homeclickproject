<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Catogery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
       //abort_unless(\Gate::allows('read_categories'), 403);

        $categories = Category::latest()->paginate(25);

        return view('dashboard.categories.index', compact('categories'));

    }//end of index

    public function create()
    {
      // abort_unless(\Gate::allows('create_categories'), 403);



        return view('dashboard.categories.create');

    }//end of create

    public function store(Request $request)
    {
          $user_id=Auth::user()->id ?? '';
//
//return $request;


        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')]];
            $rules += [$locale . '.description' => ['required', Rule::unique('category_translations', 'description')]];

        }//end of for each

        $request->validate($rules);

        Category::create($request->all()+['store_id'=>$user_id]);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of store

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));

    }//end of edit

    public function update(Request $request, Category $category)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')->ignore($category->id, 'category_id')]];

        }//end of for each

        $request->validate($rules);

        $category->update($request->all());
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of update

    public function destroy(Category $category)
    {
      //  abort_unless(\Gate::allows('category_delete'), 403);

        $category->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.categories.index');

    }//end of destroy

}//end of controller
