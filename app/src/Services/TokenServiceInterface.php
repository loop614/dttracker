<?php

declare(strict_types=1);

namespace App\Services;

interface TokenServiceInterface
{
    /**
     * @param int $userID
     *
     * @return string
     */
    public function generate(int $userID): string;

    /**
     * @param string $token
     *
     * @return bool
     */
    public function verify(string $token): bool;
}
