<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Contracts\AuthTokenInterface;
use App\Models\User;
use Firebase\JWT\JWT;

final class FirebaseJWTToken implements AuthTokenInterface
{
    public function generate(int $userId): string
    {
        $key = 'example_key';
        $user = User::find($userId);
        $payload = [
            'user' => $user,
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}
