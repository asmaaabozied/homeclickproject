<?php

namespace App\Http\Controllers\Dashboard;

use App\Catogery;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;


class CatogeryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        $subcatogery=Catogery::where('parent_id',null)->get();



        $categories = Catogery::latest()->paginate(25);



        return view('dashboard.catogeries.index', compact('categories','subcatogery'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {

        $categories=Catogery::where('parent_id',null)->get()->pluck('name','id');

//        return $categories;

        return view('dashboard.catogeries.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $rules = [ ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];


        }//end of for each



        $request->validate($rules);

      $catogery= Catogery::create($request->except(['_token','_method']));

        if($request->hasFile('icons')) {
            $thumbnail = $request->file('icons');
            $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
            Image::make($thumbnail)->resize(300, 300)->save(public_path('/uploads/' . $filename));
            $catogery->icons = $filename;
            $catogery->save();
        }


//dd($request->except(['_token','_method','icons']));

            session()->flash('success', __('site.added_successfully'));



            return redirect()->route('dashboard.catogeries.index');



    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $category=Catogery::find($id);

        $categories=Catogery::where('parent_id',null)->get()->pluck('name','id');


        return view('dashboard.catogeries.edit',compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $category=Catogery::find($id);
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];


        }//end of for each

        $request->validate($rules);

        $category->update($request->all());

        if($request->hasFile('icons')) {
            $thumbnail = $request->file('icons');
            $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
            Image::make($thumbnail)->resize(300, 300)->save(public_path('/uploads/' . $filename));
            $category->icons = $filename;
            $category->save();
        }


        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('dashboard.catogeries.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
     $sub= Catogery::find($id);
        $sub->translations()->delete();
        $sub->delete();
       return back();
    }
}
