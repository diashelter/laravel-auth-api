<?php
declare(strict_types=1);

namespace App\Infra;

use App\Domain\Contracts\UserGateway;
use App\Models\User;

final class UserGatewayDatabase implements UserGateway
{
    public function save(object $object): void
    {
        $userModel = new User([
            'name' => $object->name,
            'email' => $object->email,
            'password' => $object->password,
        ]);
        if (!$userModel->save()) {
            throw new \Exception('Error registering user');
        }
    }

    public function findByEmail(string $email): ?object
    {
        $user = User::where('email', $email)->first();
        return $user;
    }
}
