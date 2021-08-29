<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Models;

use Carbon\Carbon;
use Evp\Component\Money\Money;

class Transaction
{
    private string $uuid;
    private string $type;
    private int $userId;
    private string $userType;
    private Carbon $date;
    private Money $money;

    public function __construct()
    {
        $this->uuid = uniqid();
    }

    public function setDate(Carbon $date): Transaction
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Transaction
    {
        $this->type = $type;

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getDate(): Carbon
    {
        return $this->date;
    }

    public function setUserType(string $userType): Transaction
    {
        $this->userType = $userType;

        return $this;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function setMoney(Money $money): Transaction
    {
        $this->money = $money;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): Transaction
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }
}
