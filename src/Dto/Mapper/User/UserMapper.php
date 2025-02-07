<?php

declare(strict_types=1);

namespace App\Dto\Mapper\User;

use App\Dto\Response\User\UserDto;
use App\Entity\User;

final class UserMapper
{
    public function mapDto(User $user): UserDto
    {
        return new UserDto(
            id: $user->getId(),
            email: $user->getEmail()
        );
    }
}
