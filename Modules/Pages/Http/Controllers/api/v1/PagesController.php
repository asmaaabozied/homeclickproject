<?php

namespace Modules\Pages\Http\Controllers\api\v1;
use Illuminate\Routing\Controller;
use Modules\Pages\Entities\Page;
use Illuminate\Http\Request;
use LaravelLocalization;

class PagesController extends Controller
{
    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }
    public function index(Request $request)
    {

        // Get the Page
        $page = Page::where('slug', $request->slug)->first();
        if(!empty($page)){
            $title = $page->title;
            $name = $page->name;
            $content = strip_tags($page->content);
            return response()->json([
                'status' => 200,
                'page' => $page,
                'name'=>$name,
                'title'=>$title,
                'content' => $content,
            ]);
        }else{
            return response()->json([
                'status' =>422,
                 'msg'=>__('site.messages.no_page')
            ],422);
        }

    }
}
