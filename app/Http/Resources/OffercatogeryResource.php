<?php

namespace App\Http\Resources;

use App\Catogery;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OffercatogeryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $catogery=Catogery::where('id',$this->catogery_id)->first();

        return [

            'id'=>$catogery->id ?? '',

            'image' => !empty(asset('uploads/' . $catogery->icons) ) ? asset('uploads/' . $catogery->icons) :'',


            'name'=>$catogery->name ??  '',



            'status'=>$catogery->status ?? '',

            'start_at_discount'=>$this->start_at ?? '',

            'end_at_discount'=>$this->end_at ?? '',

            'created_at' => $catogery->created_at->diffForHumans(Carbon::now()) ?? '',

            'updated_at' =>$catogery->created_at->diffForHumans(Carbon::now()) ?? '',



        ];
    }
}
