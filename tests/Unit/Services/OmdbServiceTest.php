<?php

namespace Tests\Unit\Services;

use App\DTO\MovieDTO;
use App\Services\OmdbService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OmdbServiceTest extends TestCase
{
    private OmdbService $omdbService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->omdbService = new OmdbService();
    }

    /** @test */
    public function itFetchesMoviesCorrectly()
    {
        Http::fake([
            '*/?s=Inception&type=movie&page=1&apikey=*' => Http::response([
                'Search' => [
                    ['Year' => '2010', 'imdbID' => 'tt1375666', 'Type' => 'movie'],
                ]
            ])
        ]);

        $movies = $this->omdbService->fetchMovies('Inception', 'movie', 1);

        $this->assertInstanceOf(Collection::class, $movies);
        $this->assertCount(1, $movies);
        $this->assertEquals('2010', $movies->first()->year);
    }

    /** @test */
    public function itFetchesMovieDetailsCorrectly()
    {
        Http::fake([
            '*/?i=tt1375666&apikey=*' => Http::response([
                'Year' => '2010',
                'imdbID' => 'tt1375666',
            ])
        ]);

        $movieDto = $this->omdbService->fetchMovieDetails('tt1375666');

        $this->assertInstanceOf(MovieDTO::class, $movieDto);
        $this->assertEquals('tt1375666', $movieDto->imdbID);
        $this->assertEquals('2010', $movieDto->year);
    }
}
