<?php

namespace Saltibarsciai\CommissionTask\Tests\Service;

use Carbon\Carbon;
use Saltibarsciai\CommissionTask\Repositories\InMemoryTransactionRepository;
use Saltibarsciai\CommissionTask\Services\CurrencyConverter;
use Evp\Component\Money\Money;
use PHPUnit\Framework\TestCase;
use Saltibarsciai\CommissionTask\Exceptions\CommissionFeeManagerException;
use Saltibarsciai\CommissionTask\Models\Transaction;
use Saltibarsciai\CommissionTask\Services\CommissionFeeManager;

class InputTest extends TestCase
{
    /**
     * @throws CommissionFeeManagerException
     */
    public function testMainInput()
    {
        $currencyConverter = new CurrencyConverter();
        $transactionRepository = new InMemoryTransactionRepository();

        bcscale(10);

        $data = [
            ['2014-12-31', '4', 'private', 'withdraw', '1200.00', 'EUR'],
            ['2015-01-01', '4', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', '4', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', '1', 'private', 'deposit', '200.00', 'EUR'],
            ['2016-01-06', '2', 'business', 'withdraw', '300.00', 'EUR'],
            ['2016-01-06', '1', 'private', 'withdraw', '30000', 'JPY'],
            ['2016-01-07', '1', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-07', '1', 'private', 'withdraw', '100.00', 'USD'],
            ['2016-01-10', '1', 'private', 'withdraw', '100.00', 'EUR'],
            ['2016-01-10', '2', 'business', 'deposit', '10000.00', 'EUR'],
            ['2016-01-10', '3', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-02-15', '1', 'private', 'withdraw', '300.00', 'EUR'],
            ['2016-02-19', '5', 'private', 'withdraw', '3000000', 'JPY'],
        ];

        $commissionManager = new CommissionFeeManager($transactionRepository, $currencyConverter);

        $expected = ['0.60', '3.00', '0.00', '0.60', '1.50', '0', '0.70', '0.30', '0.30', '30.00', '0.00', '0.00', '8612'];
        $result = [];
        foreach ($data as $item) {
            $transaction = (new Transaction())
                ->setDate(Carbon::parse($item[0]))
                ->setType($item[3])
                ->setUserType($item[2])
                ->setUserId((int) $item[1])
                ->setMoney(new Money($item[4], $item[5]));

            $transactionRepository->add($transaction);

            $commissionFee = $commissionManager->calculate($transaction);

            $result[] = $commissionFee->ceil(Money::getFraction($commissionFee->getCurrency()))->getAmount();
        }

        $this->assertEquals($expected, $result);
    }
}