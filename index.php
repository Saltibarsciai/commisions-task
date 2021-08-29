<?php

declare(strict_types=1);

use Saltibarsciai\CommissionTask\Exceptions\ControllerNotFoundException;
use Saltibarsciai\CommissionTask\Services\Router;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $router = new Router();
    $controller = $router->getController();
    if($controller === null) {
        throw new ControllerNotFoundException();
    }
    $results = $controller->{$controller->method}();
    echo $results;
} catch (Exception $exception) {
    echo $exception->getMessage();
}
