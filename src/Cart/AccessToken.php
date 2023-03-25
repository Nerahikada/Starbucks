<?php

declare(strict_types=1);

namespace Nerahikada\Starbucks\Cart;

use DateTimeImmutable;

final readonly class AccessToken
{
    public function __construct(
        public string $token,
        public DateTimeImmutable $expireAt
    ) {
    }

    public function isExpired(): bool
    {
        return $this->expireAt < new DateTimeImmutable();
    }
}