<?php

declare(strict_types=1);

namespace App\Domain\UseCases\User;

use App\Domain\Contracts\EncryptHash;
use App\Domain\Contracts\UserGateway;
use App\Domain\Entity\User as EntityUser;

final class RegisterUserUseCase
{
    public function __construct(
        private readonly EncryptHash $encryptHash,
        private readonly UserGateway $userGateway,
    ) {
    }

    public function execute(InputCreateUserDto $input)
    {
        $passwordHash = $this->encryptHash->encrypt($input->password);
        $user = EntityUser::create($input->name, $input->email, $passwordHash);
        $this->userGateway->save($user);
        return $user;
    }
}

class InputCreateUserDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
