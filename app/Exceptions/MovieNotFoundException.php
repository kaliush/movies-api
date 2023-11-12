<?php

namespace App\Exceptions;

use Exception;

final class MovieNotFoundException extends Exception
{
    protected $message = 'The requested movie could not be found.';

    protected string $movieId;

    public function __construct(string $message = "", int $code = 0, Exception $previous = null, $movieId = null)
    {
        parent::__construct($message, $code, $previous);
        $this->movieId = $movieId;
    }

    public function getMovieId()
    {
        return $this->movieId;
    }
}
