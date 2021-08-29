<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Exceptions;

use Throwable;

class TransactionNoFoundException extends CommissionFeeManagerException
{
    public function __construct(string $uuid, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Transaction {$uuid} not found", $code, $previous);
    }
}
