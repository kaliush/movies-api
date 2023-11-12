<?php

namespace App\Services;

use App\DTO\MovieDTO;
use App\Exceptions\OmdbApiException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class OmdbService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.omdb.api_key');
        $this->baseUrl = config('services.omdb.base_url');
    }

    /**
     * Fetches movies from the OMDB API.
     *
     * @throws OmdbApiException if the API request fails.
     */
    public function fetchMovies(string $search, string $type, int $page): Collection
    {
        $response = Http::get("{$this->baseUrl}?s={$search}&type={$type}&page={$page}&apikey={$this->apiKey}");

        if ($response->failed()) {
            throw new OmdbApiException("Error fetching movies from OMDB API", $response->json());
        }

        $moviesArray = $response->json()['Search'] ?? [];

        return collect($moviesArray)->map(function ($movieData) {
            return MovieDTO::fromArray($movieData);
        });
    }

    /**
     * Fetches movie details from the OMDB API.
     *
     * @throws OmdbApiException
     */
    public function fetchMovieDetails(string $imdbID): MovieDTO
    {
        $response = Http::get("{$this->baseUrl}?i={$imdbID}&apikey={$this->apiKey}");

        if ($response->failed()) {
            throw new OmdbApiException("Error fetching movie details from OMDB API", $response->json());
        }

        return MovieDto::fromArray($response->json());
    }
}
