<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductssResource extends JsonResource
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

            'seller_id'=>$this->family_id ?? '',


            'description'=>$this->description,

            'status'=>$this->status,

            'image'   => asset('uploads/'. $this->image),


            'files'=>ImageResource::collection($this->images),

            'created_at' => $this->created_at->diffForHumans(Carbon::now()),

            'updated_at' => $this->updated_at->diffForHumans(Carbon::now())

        ];
    }
}
