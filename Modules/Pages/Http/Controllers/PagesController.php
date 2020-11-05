<?php

namespace Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Pages\Entities\Page;
use Modules\Pages\Http\Requests\PageRequest;
use Modules\Stadiums\Entities\Amenity;
use Modules\Stadiums\Entities\Stadium;
use Modules\Stadiums\Http\Requests\StadiumRequest;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $pages =Page::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->latest()->paginate(Paginate_number);
        return view('pages::pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('pages::pages.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PageRequest $request)
    {
        try {
            DB::beginTransaction();
            $attributes=$request->all();
            $attributes['slug']=\Illuminate\Support\Str::slug( $attributes['en']['name'], '-');
            Page::create($attributes);
            DB::commit();
        } catch (Exception $exception) {

            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.pages.index');
    }


    /**
     * Show the form for editing the specified resource.
     * @param Page $page
     * @return Response
     */
    public function edit(Page $page)
    {
        view()->share('page', $page);

        return view('pages::pages.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param StadiumRequest $request
     * @param Page $page
     * @return Response
     */
    public function update(PageRequest $request, Page $page)
    {
         try {
        DB::beginTransaction();
        $attributes=$request->all();
       $attributes['slug']=\Illuminate\Support\Str::slug( $attributes['en']['name'], '-');
        $page->update($attributes);

        DB::commit();
    } catch (Exception $exception) {

        DB::rollBack();
        throw new Exception($exception->getMessage());
    }
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param Page $page
     * @return Response
     * @throws \Exception
     */
    public function destroy( Page $page)
    {
        $page->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.pages.index');
    }
    public function statusPages($page_id)
    {
        $info= Page::find($page_id);
        $status=( $info->active == 0)?1:0;
        $info->active=$status;
        $info->save();
        session()->flash('success', __('site.updated_successfully'));
        return back();

    }
}
