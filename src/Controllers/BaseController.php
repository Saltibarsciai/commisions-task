<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Controllers;

use Saltibarsciai\CommissionTask\Interfaces\ControllerInterface;

class BaseController implements ControllerInterface
{
    public function index($methodName = null): string
    {
        return "Controller doesn't execute anything ;-)";
    }
}