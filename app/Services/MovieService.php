<?php

namespace App\Services;

use App\DTO\MovieDTO;
use App\Exceptions\MovieNotFoundException;
use App\Exceptions\TransactionFailedException;
use App\Models\Movie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

final class MovieService
{
    /**
     * @throws TransactionFailedException
     */
    public function storeMovie(MovieDTO $dto): void
    {
        try {
            DB::transaction(function () use ($dto) {
                $movieData = $dto->getMovieData();
                $movie = Movie::updateOrCreate(['imdbID' => $movieData['imdbID']], $movieData);

                $this->syncRatings($movie, $dto->ratings);
            });
        } catch (\Exception $e) {
            throw new TransactionFailedException('Failed to store movie', 0, $e);
        }
    }

    private function syncRatings(Movie $movie, array $newRatings): void
    {
        $this->updateOrCreateRatings($movie, $newRatings);
        $this->deleteOldRatings($movie, $newRatings);
    }

    private function updateOrCreateRatings(Movie $movie, array $newRatings): void
    {
        foreach ($newRatings as $rating) {
            $movie->ratings()->updateOrCreate(
                ['source' => $rating['source']],
                ['value' => $rating['value']]
            );
        }
    }

    private function deleteOldRatings(Movie $movie, array $newRatings): void
    {
        $existingSources = $movie->ratings()->pluck('source');
        $newSources = collect($newRatings)->pluck('source');
        $sourcesToDelete = $existingSources->diff($newSources);

        if ($sourcesToDelete->isNotEmpty()) {
            $movie->ratings()->whereIn('source', $sourcesToDelete)->delete();
        }
    }

    /**
     * @throws MovieNotFoundException
     */
    public function updateMovie(string $imdbID, array $attributes): Movie
    {
        try {
            $movie = Movie::findOrFail($imdbID);
        } catch (ModelNotFoundException $e) {
            throw new MovieNotFoundException("Movie with ID $imdbID not found", 0, $e);
        }

        $movie->update($attributes);
        return $movie;
    }
}
