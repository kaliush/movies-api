<?php

namespace App\GraphQL\Mutations;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class MovieMutator
{
    public function __construct(private MovieService $service)
    {
    }

    public function fetch($_, array $args): AnonymousResourceCollection
    {
        $movies = $this->service->fetchMovies($args['search'], $args['type'], $args['page']);

        foreach ($movies as $movie) {
            $movieDto = $this->service->fetchMovieDetails($movie['imdbID']);
            $this->service->storeMovie($movieDto);
        }

        return MovieResource::collection(Movie::all());
    }
}
