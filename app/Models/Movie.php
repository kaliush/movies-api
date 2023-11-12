<?php

namespace App\Models;

use App\DTO\MovieDTO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Movie extends Model
{
    use HasFactory;

    protected $primaryKey = 'imdbID';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
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
    ];

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'movie_imdbID', 'imdbID');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('imdbID', 'like', '%' . $term . '%');
    }

    public function toDto(): MovieDTO
    {
        return new MovieDTO(
            imdbID: $this->imdbID,
            type: $this->type,
            released: $this->released,
            year: $this->year,
            runtime: $this->runtime,
            genre: $this->genre,
            country: $this->country,
            poster: $this->poster,
            imdbRating: $this->imdbRating,
            imdbVotes: $this->imdbVotes,
            ratings: $this->ratings->map(function ($rating) {
                return [
                    'source' => $rating->source,
                    'value' => $rating->value,
                ];
            })->toArray()
        );
    }
}
