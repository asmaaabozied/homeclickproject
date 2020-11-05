<?php

namespace App\Http\Resources;

use App\Product;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $store = Store::where('id', $this->store_id)->first();


        return [

            'id' => $store->id ?? '',

            'name' => $store->name ?? '',

            'description' => $store->description  ?? '',

            'image' => !empty(asset('uploads/' . $store->image) ) ? asset('uploads/' . $store->image) :'',


            'created_at' => $store->created_at->diffForHumans(Carbon::now()) ?? '',

            'updated_at' => $store->updated_at->diffForHumans(Carbon::now()) ?? ''


        ];
    }
}
