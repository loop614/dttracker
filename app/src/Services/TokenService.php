<?php

declare(strict_types=1);

namespace App\Services;

use DateInterval;
use DateTime;
use MiladRahimi\Jwt\Generator;
use MiladRahimi\Jwt\Parser;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;

final class TokenService implements TokenServiceInterface
{
    private const TOKEN_ARRAY_KEYS = ['userId', 'userEmail', 'now'];
    private const TOKEN_TTL = 120;

    /**
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    /**
     * @param int $userID
     *
     * @return string
     */
    public function generate(int $userID): string
    {
        $signer = new HS256(getenv("APP_SECRET"));
        $generator = new Generator($signer);
        $user = $this->userService->getUserById($userID);

        return $generator->generate(
            [
                'userId' => $user->getId(),
                'userEmail' => $user->getEmail(),
                'now' => (new DateTime())->format(getenv("DEFAULT_DATETIME_FORMAT"))
            ]
        );
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function verify(string $token): bool
    {
        $signer = new HS256(getenv("APP_SECRET"));
        $parser = new Parser($signer);
        $claims = $parser->parse($token);

        foreach (self::TOKEN_ARRAY_KEYS as $tokenKey) {
            if (!isset($claims[$tokenKey])) {
                return false;
            }
        }

        $tokenNow = DateTime::createFromFormat(getenv("DEFAULT_DATETIME_FORMAT"), $claims["now"]);
        $ttlInterval = new DateInterval("PT" . self::TOKEN_TTL . "M");
        $tokenNow->add($ttlInterval);

        if ($tokenNow < new DateTime()) {
            return false;
        }

        return true;
    }
}
