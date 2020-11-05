<?php

namespace App\Transformers;

use App\Notification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Notification $notification)
    {

        return [
            "id" => $notification->id,
            "content" => (isset($notification->content_ar) ? $notification->content_ar : $notification->content_en),
            "type" => $notification->type,
            "created_at" => $notification->created_at
        ];
    }
}
//"id": 284,
//            "content_ar": "asmaa karam aboziedتم قبول الطلب",
//            "type": "Order",
//            "created_at": "2020-10-26T11:33:36.000000Z"
