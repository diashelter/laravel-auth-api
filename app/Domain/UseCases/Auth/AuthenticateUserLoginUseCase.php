<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Auth;

use App\Domain\Contracts\AuthTokenInterface;
use App\Domain\Contracts\EncryptHash;
use App\Domain\Contracts\UserGateway;
use App\Domain\Exception\NotFoundException;
use App\Models\User;

final class AuthenticateUserLoginUseCase
{
    public function __construct(
        private EncryptHash $encryptHash,
        private AuthTokenInterface $authToken,
        private UserGateway $userGateway,
    ) {
    }

    public function execute(InputLoginUserDto $input): OutputLoginUserDto
    {
        $user = $this->userGateway->findByEmail($input->email);
        if (!$user || !$this->encryptHash->compare($input->password, $user->password)) {
            throw new NotFoundException('Credentials invalid');
        }
        $token = $this->authToken->generate($user->id);
        return new OutputLoginUserDto($token, $user);
    }
}

class InputLoginUserDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}

class OutputLoginUserDto
{
    public function __construct(
        public readonly string $token,
        public readonly object $user,
    ) {
    }
}
