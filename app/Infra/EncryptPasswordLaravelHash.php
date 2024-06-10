<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Contracts\EncryptHash;
use Illuminate\Support\Facades\Hash;

class EncryptPasswordLaravelHash implements EncryptHash
{
    public function encrypt(string $text): string
    {
        return Hash::make($text);
    }

    public function compare(string $text, string $textHash): bool
    {
        return Hash::check($text, $textHash);
    }
}
