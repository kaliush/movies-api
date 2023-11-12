<?php

namespace App\Services;

use App\DTO\MovieDTO;
use Illuminate\Support\Facades\Http;

class OmdbService
{
    private string $apiKey;
    private string $baseUrl;

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
}
