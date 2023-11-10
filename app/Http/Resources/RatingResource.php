<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class RatingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'source' => $this->source,
            'value' => $this->value,
        ];
    }
}
