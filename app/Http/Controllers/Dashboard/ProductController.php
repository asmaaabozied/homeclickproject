<?php

namespace App\Http\Controllers\Dashboard;


use App\Category;
use App\Catogery;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Hash;

class ProductController extends Controller
{
    public function index(Request $request)
    {

       //abort_unless(\Gate::allows('read_categories'), 403);

        $products =Product::latest()->paginate(25);

        return view('dashboard.products.index', compact('products'));

    }//end of index

    public function create()
    {
      // abort_unless(\Gate::allows('create_categories'), 403);
        $catogeries=Category::get()->pluck('name','id');

        return view('dashboard.products.create',compact('catogeries'));

    }//end of create


    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }


    public function store(Request $request)
    {

        $rules = ['price'=>'required'];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];

        }//end of for each

        $request->validate($rules);

       $product= Product::create($request->except(['_token','_method','images'])+ ['family_id'=>Auth::id()]);


        if($request->file('images')) {

            $imagess = $request->file('images');


            foreach ($imagess as $images) {
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
                $image->imageable_id = $product->id;
                $image->imageable_type ='App\Product';
                $image->save();

            }
        }

        if($request->hasFile('image')) {
            $thumbnail = $request->file('image');
//            $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
            $filename = $thumbnail->hashName();
            Image::make($thumbnail)->resize(300, 300)->save(public_path('/uploads/' . $filename));
            $product->image = $filename;
            $product->save();
        }

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of store

    public function edit($id)
    {
        $product=Product::find($id);
        $catogeries=Category::get()->pluck('name','id');

        return view('dashboard.products.edit',compact('product','catogeries'));

    }//end of edit

    public function update(Request $request, $id)
    {

        $product=Product::find($id);

        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required']];
            $rules += [$locale . '.description' => ['required']];


        }//end of for each

        $request->validate($rules);

        $product->update($request->except(['_token','_method','images']));

        if($request->file('images')) {

            $imagess = $request->file('images');
\App\Image::where('imageable_id',$product->id)->where('imageable_type','App\Product')->delete();

            foreach ($imagess as $images) {
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
                $image->imageable_id = $product->id;
                $image->imageable_type ='App\Product';
                $image->save();

            }
        }

        if($request->hasFile('image')) {
            $thumbnail = $request->file('image');
//            $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
            $filename = $thumbnail->hashName();
            Image::make($thumbnail)->resize(300, 300)->save(public_path('/uploads/' . $filename));
            $product->image = $filename;
            $product->save();
        }


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of update

    public function destroy($id )
    {

      //  abort_unless(\Gate::allows('category_delete'), 403);
        $product=Product::find($id);
        $product->translations()->delete();


        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');

    }//end of destroy



    public function change_status($id){

        $info= Product::find($id);
        $status=( $info->status == 0)?1:0;
        $info->status=$status;
        $info->save();
        session()->flash('success', __('site.updated_successfully'));
        return back();


    }//end of change



}//end of controller
