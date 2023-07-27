<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity, Table('tracker_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id, Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $email;

    #[Column]
    private string $password;

    #[Column(type: Types::DECIMAL, precision: 17, scale: 5)]
    private float $balance = 1000;

    #[OneToMany(mappedBy: 'user', targetEntity: Category::class)]
    private Collection $categories;

    #[OneToMany(mappedBy: 'user', targetEntity: Expense::class)]
    private Collection $expenses;

    #[OneToMany(mappedBy: 'user', targetEntity: Income::class)]
    private Collection $incomes;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->incomes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCategories(): ArrayCollection|Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): void
    {
        $this->categories->add($category);
    }

    public function getExpenses(): ArrayCollection|Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): void
    {
        $this->expenses->add($expense);
    }

    public function getIncomes(): ArrayCollection|Collection
    {
        return $this->incomes;
    }

    public function addIncome(Income $expense): void
    {
        $this->incomes->add($expense);
    }

    public function getRoles(): array
    {
        return ["USER_ROLE"];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
