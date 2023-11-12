<?php

namespace Tests\Feature;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class GraphQLTest extends TestCase
{
    use MakesGraphQLRequests;

    public function testFetchMovies()
    {
        $response = $this->graphql('
        query {
            movies(search: "Inception", type: movie, page: 1) {
                imdbID
                type
                released
                year
                poster
                genre
                runtime
                country
                imdbRating
                imdbVotes
                ratings {
                    source
                    value
                }
            }
        }
    ');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'movies' => [
                        '*' => [
                            'imdbID',
                            'type',
                            'released',
                            'year',
                            'poster',
                            'genre',
                            'runtime',
                            'country',
                            'imdbRating',
                            'imdbVotes',
                            'ratings' => [
                                '*' => [
                                    'source',
                                    'value'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testFetchMoviesWithInvalidType()
    {
        $response = $this->graphql('
    query {
        movies(search: "", type: invalidType, year: 123, page: 0) {
            imdbID
        }
    }
    ');

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Value "invalidType" does not exist in "MovieType" enum.',
                ]
            ]
        ]);
    }

    public function testFetchMoviesWithInvalidParameters()
    {
        $response = $this->graphql('
    query {
        movies(search: "", type: movie, year: 123, page: 0) {
            imdbID
        }
    }
    ');

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Validation failed for the field [movies].',
                ]
            ]
        ]);
    }
}
