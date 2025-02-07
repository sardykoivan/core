<?php

declare(strict_types=1);

namespace App\Dto\Request;

final readonly class RegisterRequestDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
