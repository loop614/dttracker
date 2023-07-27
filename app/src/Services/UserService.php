<?php

declare(strict_types=1);

namespace App\Services;

use App\Transfer\LoginResponseTransfer;
use App\Transfer\UserTransfer;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final class UserService implements UserServiceInterface
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     *
     * @return \App\Entity\User
     */
    public function create(UserTransfer $userTransfer): User
    {
        $user = new User();
        $user->setEmail($userTransfer->email);
        $passwordHash = hash('sha256', $userTransfer->password);
        $user->setPassword($passwordHash);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param string $email
     *
     * @return \App\Entity\User|null
    */
    public function findUserByEmail(string $email): ?User
    {
        $query = $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder('pr')
            ->select('pr')
            ->where('pr.email = :email')
            ->setParameters(['email' => $email])
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param \App\Transfer\UserTransfer $userTransfer
     *
     * @return \App\Transfer\LoginResponseTransfer
     */
    public function login(UserTransfer $userTransfer): LoginResponseTransfer
    {
        $loginResponse = new LoginResponseTransfer();
        $user = $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder('pr')
            ->select('pr')
            ->where('pr.email = :email')
            ->setParameters(['email' => $userTransfer->email])
            ->getQuery()
            ->getOneOrNullResult();

        if ($user === null) {
            $loginResponse->addError('User with that email not found');
            return $loginResponse;
        }

        if ($user->getPassword() !== hash('sha256', $userTransfer->password)) {
            $loginResponse->addError('Wrong password');
            return $loginResponse;
        }

        $loginResponse->setUser($user);
        $loginResponse->setNow(new DateTimeImmutable());

        return $loginResponse;
    }

    /**
     * @param int $userId
     *
     * @return \App\Entity\User|null
     */
    public function getUserById(int $userId): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder('uqb')
            ->select('uqb')
            ->where('uqb.id = :user_id')
            ->setParameters(['user_id' => $userId])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param \App\Entity\User $user
     * @param float $amount
     *
     * @return void
     */
    public function updateBalance(User $user, float $amount): void
    {
        $newBalance = $user->getBalance() + $amount;
        $user->setBalance($newBalance);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
