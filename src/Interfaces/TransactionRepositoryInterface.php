<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Interfaces;

use Saltibarsciai\CommissionTask\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function all(): array;

    public function add(Transaction $transaction): void;

    /**
     * @param Transaction $transaction
     *
     * @return Transaction[]
     */
    public function getWithdrawThisWeekPreviousTransactions(Transaction $transaction): array;
}
