<?php
declare(strict_types=1);

namespace App\Domain\Contracts;

interface EncryptHash
{
    public function encrypt(string $text): string;
    public function compare(string $text, string $textHash): bool;
}
