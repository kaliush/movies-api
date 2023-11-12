<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
final class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imdbID' => $this->faker->unique()->bothify('tt#######'),
            'type' => $this->faker->randomElement(['movie', 'series', 'episode']),
            'released' => $this->faker->date(),
            'year' => $this->faker->year,
            'poster' => $this->faker->imageUrl(300, 300, 'movies'),
            'genre' => $this->faker->sentence(3),
            'runtime' => $this->faker->numberBetween(1, 180) . ' min',
            'country' => $this->faker->country,
            'imdbRating' => $this->faker->randomFloat(1, 0, 10),
            'imdbVotes' => $this->faker->numberBetween(0, 10000),
        ];
    }

    public function configure(): MovieFactory
    {
        return $this->afterCreating(function (Movie $movie) {
            $movie->ratings()->createMany([
                [
                    'source' => 'Internet Movie Database',
                    'value' => $this->faker->randomFloat(1, 0, 10) . '/10'
                ],
            ]);
        });
    }
}
