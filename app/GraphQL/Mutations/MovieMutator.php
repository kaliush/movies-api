<?php

namespace App\GraphQL\Mutations;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Services\MovieService;
use App\Services\OmdbService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class MovieMutator
{
    public function __construct(private MovieService $movieService, private OmdbService $omdbService)
    {
    }

    public function fetch($_, array $args): AnonymousResourceCollection
    {
        $movies = $this->omdbService->fetchMovies($args['search'], $args['type'], $args['page']);

        foreach ($movies as $movie) {
            $movieDto = $this->omdbService->fetchMovieDetails($movie['imdbID']);
            $this->movieService->storeMovie($movieDto);
        }

        return MovieResource::collection(Movie::all());
    }

    public function update($_, array $args): Movie
    {
        $imdbID = $args['imdbID'];
        unset($args['imdbID']);

        return $this->movieService->updateMovie($imdbID, $args);
    }
}
