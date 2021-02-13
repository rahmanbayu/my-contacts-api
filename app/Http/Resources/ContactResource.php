<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            "data" => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'birthday' => $this->birthday->format('m/d/Y'),
                'company' => $this->company,
                'updated' => $this->updated_at->diffForhumans()
            ],
            "links" => [
                'self' => $this->path()
            ]
        ];
    }
}
