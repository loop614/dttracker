<?php

declare(strict_types=1);

namespace App\Transfer;

final class ExpenseFilterTransfer
{
    /**
     * @var int|null
     */
    private ?int $categoryId;

    /**
     * @var int|null
     */
    private ?int $userId;

    /**
     * @var float|null
     */
    private ?float $amountGrater;

    /**
     * @var float|null
     */
    private ?float $amountLess;

    /**
     * @var string|null
     */
    private ?string $startDate;

    /**
     * @var string|null
     */
    private ?string $endDate;

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return float|null
     */
    public function getAmountLess(): ?float
    {
        return $this->amountLess;
    }

    /**
     * @param float|null $amountLess
     */
    public function setAmountLess(?float $amountLess): void
    {
        $this->amountLess = $amountLess;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @param string|null $startDate
     */
    public function setStartDate(?string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * @param string|null $endDate
     */
    public function setEndDate(?string $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return ?float
     */
    public function getAmountGrater(): ?float
    {
        return $this->amountGrater;
    }

    /**
     * @param float $amountGrater
     */
    public function setAmountGrater(float $amountGrater): void
    {
        $this->amountGrater = $amountGrater;
    }
}
