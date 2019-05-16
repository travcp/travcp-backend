<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CartItem extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result =  parent::toArray($request);
        $result["booking"] = new Booking($this->booking);
        return $result;
    }
}
