<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface AuthTokenInterface
{
    public function generate(int $userId): string;
}
