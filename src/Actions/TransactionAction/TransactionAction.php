<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Actions\TransactionAction;

use Carbon\Carbon;
use Evp\Component\Money\Money;
use Exception;
use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Interfaces\TransactionRepositoryInterface;
use Saltibarsciai\CommissionTask\Services\CommissionFeeManager;
use Saltibarsciai\CommissionTask\Services\CurrencyConverter;
use Saltibarsciai\CommissionTask\Services\InputManager;

class TransactionAction
{
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function calculateFees()
    {
        try {
            $inputManager = new InputManager('payload');
            $file = $inputManager->openFile();
            $currencyConverter = new CurrencyConverter();
            $commissionFeeManager = new CommissionFeeManager($this->transactionRepository, $currencyConverter);

            while ($row = fgetcsv($file)) {
                try {
                    $transaction = $this->makeTransaction($row);
                    $this->transactionRepository->add($transaction);
                    $fee = $commissionFeeManager->calculate($transaction);
                    $this->output($fee);
                } catch (Exception $exception) {
                    echo $exception->getMessage();
                }
            }

            $inputManager->closeFile();
        } catch (Exception $exception) {
            echo 'Failed fees calculation |  ' . $exception->getMessage();
        }
    }

    private function makeTransaction($row): Transaction
    {
        $transaction = new Transaction();
        return $transaction
            ->setDate(Carbon::parse($row[0]))
            ->setType($row[3])
            ->setUserId((int) $row[1])
            ->setUserType($row[2])
            ->setMoney(new Money($row[4], $row[5]));
    }

    private function output($fee)
    {
        $fraction = Money::getFraction($fee->getCurrency());
        $amount = $fee->ceil($fraction)->getAmount();
        fwrite(STDOUT, $amount . PHP_EOL);
    }

}