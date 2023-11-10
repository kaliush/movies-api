<?php

namespace App\GraphQL\Mutations;

use App\DTO\MovieDTO;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final readonly class MovieMutator
{
    public function __construct(private MovieService $service)
    {
    }

    public function fetch($_, array $args)
    {
        $movies = $this->service->fetchMovies($args['search'], $args['type'], $args['page']);

        foreach ($movies as $movie) {
            $movieDto = $this->service->fetchMovieDetails($movie['imdbID']);
            $this->service->storeMovie($movieDto);
        }

        return MovieResource::collection(Movie::all());
    }
}
