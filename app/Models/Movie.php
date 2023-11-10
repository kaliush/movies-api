<?php

namespace App\Models;

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
}
