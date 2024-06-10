<?php

declare(strict_types=1);

namespace App\Domain\Entity;

final class User
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    public static function create(string $name, string $email, string $password)
    {
        return new self(
            null,
            $name,
            $email,
            $password
        );
    }
}
