<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Controllers;

use Exception;
use Saltibarsciai\CommissionTask\Actions\TransactionAction\TransactionAction;
use Saltibarsciai\CommissionTask\Interfaces\TransactionRepositoryInterface;

class TransactionsController extends BaseController
{
    /**
     * @var TransactionRepositoryInterface
     */
    public TransactionRepositoryInterface $transactionRepository;
    /**
     * @var mixed|string
     */
    public $method;

    public function __construct(TransactionRepositoryInterface $transactionRepository, $method = 'index')
    {
        $this->transactionRepository = $transactionRepository;
        $this->method = $method;
    }

    /**
     * @throws Exception
     */
    public function index($methodName = null): string
    {
        $transactionAction = new TransactionAction($this->transactionRepository);
        $transactionAction->calculateFees();
        return '';
    }
}