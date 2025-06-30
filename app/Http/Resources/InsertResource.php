<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsertResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'link' => $this->link,
            'price' => $this->price,
            'people_count' => $this->people_count,
            'per_person_count' => $this->per_person_count,
            'count' => $this->count,
            'address' => $this->address,
            'bank' => $this->bank,
            'account_number' => $this->account_number,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'owner' => auth()->id() === $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'buys' => $this->whenLoaded('buys', function () {
                return BuyResource::collection($this->buys);
            }),
            'tracking_number' => $this->tracking_number,
            'courier' => $this->courier,
        ];
    }
}
