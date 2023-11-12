<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MovieDTO
{
    public function __construct(
        public string  $imdbID,
        public ?string $type,
        public ?string $released,
        public ?int    $year,
        public ?string $runtime,
        public ?string $genre,
        public ?string $country,
        public ?string $poster,
        public ?float  $imdbRating,
        public ?int    $imdbVotes,
        public array   $ratings
    ) {
        $this->ratings = $this->transformRatings($ratings);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            imdbID: $data['imdbID'] ?? '',
            type: $data['Type'] ?? null,
            released: self::formatDate($data['Released'] ?? null),
            year: isset($data['Year']) ? (int)$data['Year'] : null,
            runtime: $data['Runtime'] ?? null,
            genre: $data['Genre'] ?? null,
            country: $data['Country'] ?? null,
            poster: $data['Poster'] ?? null,
            imdbRating: isset($data['imdbRating']) ? floatval($data['imdbRating']) : null,
            imdbVotes: isset($data['imdbVotes']) ? self::parseImdbVotes($data['imdbVotes']) : null,
            ratings: $data['Ratings'] ?? []
        );
    }

    private function transformRatings(array $ratings): array
    {
        return array_map(static function ($rating) {
            return [
                'source' => $rating['Source'] ?? null,
                'value' => $rating['Value'] ?? null,
            ];
        }, $ratings);
    }

    private static function parseImdbVotes($imdbVotes): ?int
    {
        if (is_numeric($imdbVotes)) {
            return (int)$imdbVotes;
        } elseif (is_string($imdbVotes)) {
            return (int)str_replace(',', '', $imdbVotes);
        }

        return null;
    }

    private static function formatDate(?string $date): ?string
    {
        if ($date === 'N/A' || $date === null) {
            return null; // Return null for not released movies
        }

        try {
            return Carbon::createFromFormat('d M Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null; // Return null if the date format is incorrect
        }
    }

    public function getMovieData(): array
    {
        return [
            'imdbID' => $this->imdbID,
            'type' => $this->type,
            'released' => $this->released,
            'year' => $this->year,
            'runtime' => $this->runtime,
            'genre' => $this->genre,
            'country' => $this->country,
            'poster' => $this->poster,
            'imdbRating' => $this->imdbRating,
            'imdbVotes' => $this->imdbVotes,
        ];
    }
}
