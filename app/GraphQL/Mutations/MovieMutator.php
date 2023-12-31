<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\OmdbApiException;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Services\MovieService;
use App\Services\OmdbService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

final readonly class MovieMutator
{
    public function __construct(private MovieService $movieService, private OmdbService $omdbService)
    {
    }

    public function fetch($_, array $args): AnonymousResourceCollection
    {
        $fetchedMovies = [];

        try {
            $movies = $this->omdbService->fetchMovies($args['search'], $args['type'], $args['page']);

            foreach ($movies as $movie) {
                try {
                    $movieDto = $this->omdbService->fetchMovieDetails($movie->imdbID);
                    $storedMovie = $this->movieService->storeMovie($movieDto);
                    $fetchedMovies[] = $storedMovie;
                } catch (OmdbApiException $e) {
                    Log::error($e->getMessage());
                }
            }
        } catch (OmdbApiException $e) {
            Log::error($e->getMessage());
            return MovieResource::collection(collect());
        }

        return MovieResource::collection(collect($fetchedMovies));
    }

    public function update($_, array $args): Movie
    {
        $imdbID = $args['imdbID'];
        unset($args['imdbID']);

        return $this->movieService->updateMovie($imdbID, $args);
    }
}
