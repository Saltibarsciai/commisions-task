<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Enum;

use Exception;

final class UserType
{
    const BUSINESS = 'business';

    const PRIVATE = 'private';

    /**
     * UserType constructor.
     *
     * @throws Exception
     */
    private function __construct()
    {
        throw new Exception("Supposed to be used statically");
    }
}
