<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Rating extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'movie_imdbID',
        'source',
        'value',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'imdbID', 'movie_imdbID');
    }
}
