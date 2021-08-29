<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Enum;

use Exception;

final class OperationType
{
    const DEPOSIT = 'deposit';

    const WITHDRAW = 'withdraw';

    /**
     * OperationType constructor.
     *
     * @throws Exception
     */
    private function __construct()
    {
        throw new Exception("Supposed to be used statically");
    }
}
