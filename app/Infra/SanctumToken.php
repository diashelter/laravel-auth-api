<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Contracts\AuthTokenInterface;
use App\Models\User;

class SanctumToken implements AuthTokenInterface
{
    public function generate(int $userId): string
    {
        $user = User::find($userId);
        $user->tokens()->delete();
        return $user->createToken("{$user->name}::{$user->id}")->plainTextToken;
    }
}
