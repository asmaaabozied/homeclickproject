<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
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

            'phone'=>$this->phone,

            'latitude'=>$this->lat ?? '',

            'longitude'=>$this->lon ?? '',


            'Delivery value'=>$this->value,

            'work hours'=>$this->hours,


            'description'=>$this->description,

            'status'=>$this->status,

            'rating'=>$this->ratings()->avg('rate'),

            'category'=>ComplainResource::collection($this->categories),


            'image'   => asset('uploads/'. $this->image),

            'icon'   => asset('uploads/'. $this->icon),


            'files'=>ImageResource::collection($this->images),

            'created_at' => $this->created_at->diffForHumans(Carbon::now()),
            'updated_at' => $this->updated_at->diffForHumans(Carbon::now())

        ];
    }
}
