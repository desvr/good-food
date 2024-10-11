<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'chat_id'    => $this->chat_id,
            'user_id'    => $this->user_id,
            'from_admin' => $this->from_admin,
            'content'    => $this->content,
            'is_read'    => $this->is_read,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
