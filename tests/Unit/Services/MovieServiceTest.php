<?php

namespace Tests\Unit\Services;

use App\DTO\MovieDTO;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieServiceTest extends TestCase
{
    use RefreshDatabase;

    private MovieService $movieService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->movieService = new MovieService();
    }

    /** @test */
    public function itStoresAMovieCorrectly()
    {
        $movieData = new MovieDTO('tt1234567', 'movie', '19.05.2020', '2020', '120 min', 'Action', 'Argentina', 'some poster url', '7.8', '10000', []);
        $this->movieService->storeMovie($movieData);

        $movie = Movie::firstOrFail();
        $this->assertEquals('tt1234567', $movie->getKey());
        $this->assertEquals('2020', $movie->year);
    }

    /** @test */
    public function itUpdatesAnExistingMovieCorrectly()
    {
        Movie::create(['imdbID' => 'tt1234567', 'runtime' => 'old runtime', 'year' => '2020']);

        $updatedMovieData = new MovieDTO('tt1234567', 'movie', '19.05.2020', '2021', '120 min', 'Action', 'Argentina', 'some poster url', '7.8', '10000', []);
        $this->movieService->storeMovie($updatedMovieData);

        $updatedMovie = Movie::firstOrFail();
        $this->assertEquals('120 min', $updatedMovie->runtime);
        $this->assertEquals('2021', $updatedMovie->year);
    }
}
