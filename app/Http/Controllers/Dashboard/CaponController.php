<?php

namespace App\Http\Controllers\Dashboard;

use App\Capon;
use App\Category;
use App\Catogery;
use App\Mail\MailMessage;
use App\Offer;
use App\Product;
use Carbon\Carbon;

//use App\Image;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

//use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;


class CaponController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $capons = Capon::latest()->paginate(Paginate_number);


        return view('dashboard.caponss.index', compact('capons'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $catogeries = Category::get()->pluck('name', 'id');

        $products = Product::get()->pluck('name', 'id');


        return view('dashboard.caponss.create', compact('catogeries', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */


    public function store(Request $request)
    {


        $capon = Capon::create($request->except(['_token', '_method']));


        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.capons.index');

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
     * @param Consultation $consultation
     * @return Response
     */
    public function edit(Capon $capon)
    {
        $catogeries = Category::get()->pluck('name', 'id');

        $products = Product::get()->pluck('name', 'id');


        return view('dashboard.caponss.edit', compact('capon', 'catogeries', 'products'));


    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Consultation $consultation
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $capon = Capon::find($id);


        $attributes = $request->all();


        $capon->update($attributes);


        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('dashboard.capons.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param Consultation $consultation
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        Capon::find($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));

        return back();
    }


}
