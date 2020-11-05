<?php

namespace App\Http\Resources;

use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $product=Product::where('id',$this->product_id)->first();

        return [

            'id'=>$product->id,

            'name'=>$product->name,

            'description'=>$product->description,

            'image' => asset('uploads/'. $product->image),


            'created_at' => $product->created_at->diffForHumans(Carbon::now()) ,

            'updated_at' => $product->updated_at->diffForHumans(Carbon::now())


        ];
    }
}
