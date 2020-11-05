<?php

namespace App\Http\Resources;

use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        return [

            'id'=>$this->id,

            'name'=>$this->name,

            'description'=>$this->description,

            'price'=>$this->price,

            'rating'=>$this->ratings()->avg('rate'),

            'image' => asset('uploads/'. $this->image),


            'created_at' => $this->created_at->diffForHumans(Carbon::now()) ,

            'updated_at' => $this->updated_at->diffForHumans(Carbon::now())


        ];
    }
}
