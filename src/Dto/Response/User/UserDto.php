<?php

declare(strict_types=1);

namespace App\Dto\Response\User;

final readonly class UserDto
{
    public function __construct(public string $id, public string $email)
    {
    }
}
