<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Repositories;

use Carbon\Carbon;
use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Enum\OperationType;
use Saltibarsciai\CommissionTask\Exceptions\TransactionNoFoundException;
use Saltibarsciai\CommissionTask\Interfaces\TransactionRepositoryInterface;

class InMemoryTransactionRepository implements TransactionRepositoryInterface
{
    private array $transactions = [];

    public function all(): array
    {
        return $this->transactions;
    }

    public function add(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     *
     * @param Transaction $transaction
     *
     * @return array
     *
     * @throws TransactionNoFoundException
     */
    public function getWithdrawThisWeekPreviousTransactions(Transaction $transaction): array
    {
        $index = $this->getIndexByTransactionUuid($transaction->getUuid());

        $startWeekDate = Carbon::parse($transaction->getDate())->startOfWeek(Carbon::MONDAY);

        $transactions = $this->all();

        $thisWeekTransactions = [];

        for ($i = $index - 1; $i >= 0; --$i) {
            if ($transactions[$i]->getDate() < $startWeekDate) {
                break;
            }

            if ($transactions[$i]->getUserId() === $transaction->getUserId() && $transactions[$i]->getType() === OperationType::WITHDRAW) {
                $thisWeekTransactions[] = $transactions[$i];
            }
        }

        return $thisWeekTransactions;
    }

    /**
     * Get index of transaction (NOT id) by its uuid.
     *
     * @param string $uuid
     *
     * @return int
     *
     * @throws TransactionNoFoundException
     */
    private function getIndexByTransactionUuid(string $uuid): int
    {
        foreach ($this->all() as $index => $transaction) {
            if ($transaction->getUuid() === $uuid) {
                return $index;
            }
        }

        throw new TransactionNoFoundException($uuid);
    }
}
