<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Resources\Json\JsonResource;

class App_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        return parent::toArray($request);
    }


   
    
}
