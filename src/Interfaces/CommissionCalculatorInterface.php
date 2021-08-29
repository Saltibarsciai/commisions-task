<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Interfaces;

use Saltibarsciai\CommissionTask\Models\Transaction;
use Evp\Component\Money\Money;

interface CommissionCalculatorInterface
{
    public function calculate(Transaction $transaction): Money;
}
