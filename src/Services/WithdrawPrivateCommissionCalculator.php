<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Services;

use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Interfaces\CommissionCalculatorInterface;
use Saltibarsciai\CommissionTask\Interfaces\CurrencyConverterInterface;
use Saltibarsciai\CommissionTask\Interfaces\TransactionRepositoryInterface;
use Evp\Component\Money\Money;
use Evp\Component\Money\MoneyException;

class WithdrawPrivateCommissionCalculator implements CommissionCalculatorInterface
{
    private $commissionRate;

    private $freeOperations;

    private Money $freeChargePerWeek;

    private CurrencyConverterInterface $currencyConverter;

    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(CurrencyConverterInterface $currencyConverter, TransactionRepositoryInterface $transactionRepository)
    {
        $config = include('config/commision.php');

        $this->commissionRate = $config['withdrawCommissionPrivate'];

        $this->freeOperations = $config['withdrawPrivateFreeTransactions'];

        $this->freeChargePerWeek = new Money($config['withdrawCommissionPrivateDiscount'], 'EUR');

        $this->currencyConverter = $currencyConverter;

        $this->transactionRepository = $transactionRepository;
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
        $thisWeekTransactions = $this->transactionRepository->getWithdrawThisWeekPreviousTransactions($transaction);

        if (count($thisWeekTransactions) >= $this->freeOperations) {
            return new Money(
                bcmul($transaction->getMoney()->getAmount(), (string) $this->commissionRate),
                $transaction->getMoney()->getCurrency()
            );
        }

        $prevTotalAmountEur = new Money(0, 'EUR');

        foreach ($thisWeekTransactions as $thisWeekTransaction) {
            $prevTotalAmountEur = $prevTotalAmountEur->add($this->currencyConverter->convert(
                $thisWeekTransaction->getMoney(),
                'EUR'
            ));
        }

        $currentAmountEur = $this->currencyConverter->convert(
            $transaction->getMoney(),
            'EUR'
        );

        if ($prevTotalAmountEur->isGt($this->freeChargePerWeek)) {
            $amount = $currentAmountEur;
        } else {
            $amount = $prevTotalAmountEur->add($currentAmountEur)->sub($this->freeChargePerWeek);
        }

        if ($amount->isGt(new Money(0, 'EUR'))) {
            return $this->currencyConverter->convert(
                $amount,
                $transaction->getMoney()->getCurrency()
            )->mul($this->commissionRate);
        }

        return new Money(0, $transaction->getMoney()->getCurrency());
    }
}
