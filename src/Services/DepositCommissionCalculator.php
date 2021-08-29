<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Services;

use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Interfaces\CommissionCalculatorInterface;
use Saltibarsciai\CommissionTask\Interfaces\CurrencyConverterInterface;
use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyException;

class DepositCommissionCalculator implements CommissionCalculatorInterface
{

    private CurrencyConverterInterface $currencyConverter;
    /**
     * @var mixed
     */
    private $commissionRate;

    public function __construct(CurrencyConverterInterface $currencyConverter)
    {
        $config = include('config/commision.php');

        $this->commissionRate = $config['depositCommissionPercent'];

        $this->currencyConverter = $currencyConverter;
    }

    /**
     * @param Transaction $transaction
     *
     * @return Money
     *
     * @throws MoneyException
     */
    public function calculate(Transaction $transaction): Money
    {
        $commissionFeeOriginal = $transaction->getMoney()->mul($this->commissionRate);

        $commissionFeeEur = $this->currencyConverter->convert($commissionFeeOriginal, 'EUR');

        return $this->currencyConverter->convert($commissionFeeEur, $transaction->getMoney()->getCurrency());
    }
}
