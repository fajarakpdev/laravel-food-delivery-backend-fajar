<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "address" => $this->address,
            "license_plate" => $this->license_plate,
            "restaurant_name" => $this->restaurant_name,
            "restaurant_address" => $this->restaurant_address,
            "restaurant_description" => $this->restaurant_description,
            "profile_photo" => $this->profile_photo,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "role" => $this->role,
        ];
    }
}
