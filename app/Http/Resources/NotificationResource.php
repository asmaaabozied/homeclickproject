<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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


            "content" => (isset($this->content_ar) ? $this->content_ar : $this->content_en),

            'created_at'=>$this->created_at ?? '',

            'updated_at' => $this->updated_at?? ''



        ];
    }
}
