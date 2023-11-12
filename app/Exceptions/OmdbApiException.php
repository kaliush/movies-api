<?php

namespace App\Exceptions;

use Exception;

final class OmdbApiException extends Exception
{
    public function __construct(string $message, private readonly ?string $response = null)
    {
        parent::__construct($message);
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }
}
