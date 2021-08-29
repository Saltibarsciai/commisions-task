<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Services;

use Exception;
use Saltibarsciai\CommissionTask\Controllers\TransactionsController;
use Saltibarsciai\CommissionTask\Repositories\InMemoryTransactionRepository;

class Router
{
    public $route;
    private $routesConfig;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $options = getopt('', ['route:']);
        if (empty($options['route'])) {
            throw new Exception('Parameter --route is required');
        }
        $this->route = $options['route'];
        $this->routesConfig = include('config/routes.php');
    }

    public function getController(): ?TransactionsController
    {
        switch ($this->route) {
            case $this->routesConfig['calculate-fees']:
                return new TransactionsController(new InMemoryTransactionRepository());
            default:
                return null;
        }
    }
}