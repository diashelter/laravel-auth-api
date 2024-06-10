<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface UserGateway
{
    public function save(object $object): void;
    public function findByEmail(string $email): ?object;
}
