<?php

namespace App\Services;

use App\DTO\MovieDTO;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MovieService
{
    public function storeMovie(MovieDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $movieData = $dto->getMovieData();
            $movie = Movie::updateOrCreate(['imdbID' => $movieData['imdbID']], $movieData);
            $this->updateRatings($movie, $dto->ratings);
        });
    }

    protected function updateRatings(Movie $movie, array $newRatings): void
    {
        $currentRatings = $movie->ratings->keyBy('source');

        foreach ($newRatings as $rating) {
            $currentRating = $currentRatings->pull($rating['source']);
            if ($currentRating) {
                $currentRating->update(['value' => $rating['value']]);
            } else {
                $movie->ratings()->create($rating);
            }
        }

        foreach ($currentRatings as $oldRating) {
            $oldRating->delete();
        }
    }

    public function updateMovie(string $imdbID, array $attributes): Movie
    {
        $movie = Movie::findOrFail($imdbID);
        $movie->update($attributes);
        return $movie;
    }
}
