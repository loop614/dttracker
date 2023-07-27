<?php

declare(strict_types=1);

namespace App\Service\Tests;

use App\Services\UserService;
use App\Transfer\UserTransfer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
{
    /**
     * @var \App\Services\UserService
     */
    private UserService $sut;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->sut = $container->get(UserService::class);
    }

    /**
     * @return void
     */
    public function testBalanceCalculations(): void
    {
        $user = new UserTransfer("test@test.com", "test");
        $userEntity = $this->sut->create($user);
        $this->sut->updateBalance($userEntity, 26);
        $updatedUser = $this->sut->getUserById($userEntity->getId());
        $this->assertSame($updatedUser->getBalance(), 1026.0);
        $this->sut->updateBalance($userEntity, -26);
        $updatedUser = $this->sut->getUserById($userEntity->getId());
        $this->assertSame($updatedUser->getBalance(), 1000.0);
    }
}
