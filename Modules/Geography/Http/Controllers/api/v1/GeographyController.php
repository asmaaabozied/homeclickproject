<?php

namespace Modules\Geography\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Geography\Entities\Geography;

use LaravelLocalization;
class GeographyController extends Controller
{
    public function __construct()
    {
        $local=(!empty(Request()->route()))?(Request()->route()->parameters()['locale']): 'en';
        LaravelLocalization::setLocale($local);
    }
    public function index()
    {
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');

        $cities = Geography::whereNotNull('parent_id')
                            ->withTranslation()
                            ->orderByTranslation('name','asc')
                            ->get()
                            ->groupBy('parent_id');

        $countries_Array=[];
        foreach ($countries as $key => $value) {
            // "1": "AUE",
            $city_data=[];
            foreach ($cities[$key] as $city_key => $city_value) {
               $city_data[]=[
                    "city_id"=> $city_value["id"],
                    "city_name"=> $city_value["name"],
               ];
            }
            $countries_Array[]=[
                "country_id"=> $key,
                "country_name"=> $value,
                "cities"=> $city_data
            ];
        }

        //IF I want It Less Size Withput Arrangment
        // $cities = Geography::whereNotNull('parent_id')
        //                     ->withTranslation()->get()
        //                     ->groupBy('parent_id');
        // $cities = Geography::listsTranslations('name')->get()->toArray();
        return response()->json(['status'=>200 ,'data'=>$countries_Array]);

    }

    public function index_object()
    {
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        $cities = Geography::whereNotNull('parent_id')
                            ->withTranslation()
                            ->orderByTranslation('name','asc')
                            ->get()
                            ->groupBy('parent_id');

        //IF I want It Less Size Withput Arrangment
        // $cities = Geography::whereNotNull('parent_id')
        //                     ->withTranslation()->get()
        //                     ->groupBy('parent_id');
        // $cities = Geography::listsTranslations('name')->get()->toArray();
        return response()->json(['status'=>200 ,'data'=>['countries'=>$countries,'cities'=> $cities]]);

    }

    public function countries()
    {
        $countries = Geography::whereNull('parent_id')->get()->pluck('name','id');
        return response()->json(['status'=>200 ,'data'=>$countries]);
    }

    public function cities($lang,$country_id)
    {
        // dd($country_id);
        $cities = Geography::where('parent_id','=' ,$country_id)->get()
                                ->pluck('name','id');
        return response()->json(['status'=>200 ,'data'=>$cities]);

    }


}
