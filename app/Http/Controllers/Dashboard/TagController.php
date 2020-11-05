<?php

namespace App\Http\Controllers\Dashboard;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index(Request $request)
    {

        $tags = Tag::when($request->search, function ($q) use ($request) {
            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->latest()->paginate(Paginate_number);

        return view('dashboard.tags.index', compact('tags'));

    }//end of index

    public function create()
    {
        return view('dashboard.tags.create');

    }//end of create

    public function store(Request $request)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('tag_translations', 'name')]];

        }//end of for each

        $request->validate($rules);

        Tag::create($request->all());
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.tags.index');

    }//end of store

    public function edit(Tag $tag)
    {
        return view('dashboard.tags.edit', compact('tag'));

    }//end of edit

    public function update(Request $request, Tag $tag)
    {
        $rules = [];

        foreach (config('translatable.locales') as $locale) {
            $rules += [$locale . '.name' => ['required', Rule::unique('tag_translations', 'name')->ignore($tag->id, 'tag_id')]];
        }//end of for each

        $request->validate($rules);

        $tag->update($request->all());
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.tags.index');

    }//end of update

    public function destroy(Tag $tag)
    {
        $tag->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.tags.index');

    }//end of destroy

}//end of controller
