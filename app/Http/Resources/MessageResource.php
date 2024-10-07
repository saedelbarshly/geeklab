<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'sender' => (string) $this->sender?->username,
            'recipient' => (string) $this->recipient?->username,
            'content' => (string) $this->content,
            'is_seen' => (bool) $this->seen
        ];
    }
}
