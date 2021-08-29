<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Services;

use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Enum\OperationType;
use Saltibarsciai\CommissionTask\Enum\UserType;
use Saltibarsciai\CommissionTask\Exceptions\CommissionFeeManagerException;
use Saltibarsciai\CommissionTask\Interfaces\CommissionCalculatorInterface;
use Saltibarsciai\CommissionTask\Interfaces\CurrencyConverterInterface;
use Saltibarsciai\CommissionTask\Interfaces\TransactionRepositoryInterface;
use Evp\Component\Money\Money;

class CommissionFeeManager
{
    private int $scale = 10;

    private TransactionRepositoryInterface $transactionRepository;

    private CurrencyConverterInterface $currencyConverter;

    public function __construct(TransactionRepositoryInterface $transactionRepository, CurrencyConverterInterface $currencyConverter)
    {
        $this->transactionRepository = $transactionRepository;

        $this->currencyConverter = $currencyConverter;

        bcscale($this->scale);
    }

    /**
     * @param Transaction $transaction
     *
     * @return Money
     *
     * @throws CommissionFeeManagerException
     */
    public function calculate(Transaction $transaction): Money
    {
        $calculator = $this->getCalculator($transaction->getType(), $transaction->getUserType());

        return $calculator->calculate($transaction);
    }

    /**
     * Factory method for getting particular calculator.
     *
     * @param string $transactionType
     * @param string $userType
     *
     * @return CommissionCalculatorInterface
     *
     * @throws CommissionFeeManagerException
     */
    private function getCalculator(string $transactionType, string $userType): CommissionCalculatorInterface
    {
        $calculator = null;

        if (OperationType::DEPOSIT === $transactionType) {
            $calculator = new DepositCommissionCalculator($this->currencyConverter);
        } elseif (OperationType::WITHDRAW === $transactionType) {
            if (UserType::BUSINESS === $userType) {
                $calculator = new WithdrawBusinessCommissionCalculator($this->currencyConverter);
            } elseif (UserType::PRIVATE === $userType) {
                $calculator = new WithdrawPrivateCommissionCalculator($this->currencyConverter, $this->transactionRepository);
            }
        }

        if (is_null($calculator)) {
            throw new CommissionFeeManagerException('Commission calculator not found');
        }

        return $calculator;
    }
}
