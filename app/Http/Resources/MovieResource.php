<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'imdbID' => $this->imdbID,
            'type' => $this->type,
            'released' => $this->released,
            'year' => $this->year,
            'runtime' => $this->runtime,
            'genre' => $this->genre,
            'country' => $this->country,
            'poster' => $this->poster,
            'imdbRating' => $this->imdbRating,
            'imdbVotes' => $this->imdbVotes,
            'ratings' => RatingResource::collection($this->ratings),
        ];
    }
}
