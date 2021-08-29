<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Services;

use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Interfaces\CommissionCalculatorInterface;
use Saltibarsciai\CommissionTask\Interfaces\CurrencyConverterInterface;
use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyException;

class WithdrawBusinessCommissionCalculator implements CommissionCalculatorInterface
{
    private Money $minCommission;

    private CurrencyConverterInterface $currencyConverter;
    /**
     * @var mixed
     */
    private $commissionRate;

    public function __construct(CurrencyConverterInterface $currencyConverter)
    {
        $config = include('config/commision.php');

        $this->commissionRate = $config['withdrawCommissionBusiness'];

        $this->minCommission = new Money($config['withdrawCommissionBusinessLimit'], 'EUR');

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
        $commissionFee = $transaction->getMoney()->mul($this->commissionRate);

        $commissionFeeEur = $this->currencyConverter->convert($commissionFee, 'EUR');

        if ($commissionFee->isLt($this->minCommission)) {
            $commissionFeeEur = $this->minCommission;
        }

        return $this->currencyConverter->convert($commissionFeeEur, $transaction->getMoney()->getCurrency());
    }
}
