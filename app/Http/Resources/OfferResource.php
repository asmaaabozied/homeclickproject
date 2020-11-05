<?php

namespace App\Http\Resources;

use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $product = Product::where('id', $this->product_id)->first();


            return [

                'id' => $product->id ?? '',


                'image' => !empty(asset('uploads/' . $product->image) ) ? asset('uploads/' . $product->image) :'',


                'name' => $product->name ?? '',

                'description' => $product->description ?? '',

                'status' => $product->status ?? '',

                'start_at_discount' => $this->start_at ?? '',

                'end_at_discount' => $this->end_at?? '',

                'created_at' => $product->created_at->diffForHumans(Carbon::now()) ?? '' ,

                'updated_at' => $product->created_at->diffForHumans(Carbon::now()) ?? '' ,


            ];


    }
}
