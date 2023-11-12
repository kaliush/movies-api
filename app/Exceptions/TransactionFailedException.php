<?php

namespace App\Exceptions;

use Exception;

final class TransactionFailedException extends Exception
{
    protected $message = 'Database transaction failed.';

    protected $details;

    public function __construct(string $message = "", int $code = 0, Exception $previous = null, $details = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
