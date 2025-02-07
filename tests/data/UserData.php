<?php

declare(strict_types=1);

namespace App\Tests\data;

use App\Entity\User;

final class UserData
{
    public static function getUser(): User
    {
        return new User(
            id: 'a93064a0-5818-4450-a86c-299c82676619',
            email: 'user@test.com',
            password: '$2y$13$uCMctpS/0LtxZQX41MbQceKjUP0KbF/LQkBC.Gp9jyKaSVRv0yNRK' // admin
        );
    }
}
