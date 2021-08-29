<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Exceptions;

use Throwable;

class CurrencyNotSupportedException extends CommissionFeeManagerException
{
    public function __construct(string $currency, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Currency {$currency} not supported", $code, $previous);
    }
}
