<?php

namespace App\Exceptions;

use RuntimeException;

class InvoiceArithmeticException extends RuntimeException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct("[FERRO Invoice Arithmetic Error] {$message}", $code, $previous);
    }
}
