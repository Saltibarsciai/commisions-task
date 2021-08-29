<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Exceptions;

use Exception;

class ControllerNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("Controller not found", 404, null);
    }
}