<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(
            id: 'c5228eb4-6f87-4c0a-8050-88a88c1a411c',
            email: 'user@fixture.com',
            password: '$2y$13$uCMctpS/0LtxZQX41MbQceKjUP0KbF/LQkBC.Gp9jyKaSVRv0yNRK' // admin
        );
        $manager->persist($user);
        $manager->flush();
    }
}
