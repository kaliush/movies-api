<?php

namespace App\Services;

use App\DTO\MovieDTO;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

final class MovieService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.omdb.api_key');
        $this->baseUrl = config('services.omdb.base_url');
    }

    public function fetchMovies(string $search, string $type, int $page): array
    {
        $response = Http::get("{$this->baseUrl}?s={$search}&type={$type}&page={$page}&apikey={$this->apiKey}");

        return $response->json()['Search'] ?? [];
    }

    public function fetchMovieDetails(string $imdbID): MovieDTO
    {
        $response = Http::get("{$this->baseUrl}?i={$imdbID}&apikey={$this->apiKey}");

        return MovieDto::fromArray($response->json());
    }

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
}
