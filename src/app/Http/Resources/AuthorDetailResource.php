<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'author_id' => $this->id,
            'author_name' => $this->name,
            'author_email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'author_bio' => $this->author_bio,
            'books' => BookDetailResource::collection($this->books)
        ];
    }
}
