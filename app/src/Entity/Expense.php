<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('expense')]
class Expense
{
    use HasTimestamps;

    #[Id, Column, GeneratedValue]
    private int $id;

    #[Column(type: Types::DECIMAL, precision: 17, scale: 5)]
    private float $amount;

    #[Column(nullable: true)]
    private string $description;

    #[ManyToOne(inversedBy: 'expenses')]
    private User $user;

    #[ManyToOne(inversedBy: 'expenses')]
    private Category $category;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param \App\Entity\User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \App\Entity\Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param \App\Entity\Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}
