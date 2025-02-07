<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\RegisterRequestDto;
use App\Entity\User;
use App\Exception\Http\EntityExistsException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Random\RandomException;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class UserService
{
    public function __construct(
        private UserRepository $repository,
        private UuidGenerator $uuidGenerator,
        private PasswordService $passwordService,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    /**
     * @throws RandomException
     */
    public function createUser(RegisterRequestDto $dto): void
    {
        if ($this->repository->findByEmail($dto->email) !== null) {
            throw new EntityExistsException(
                400,
                'User with email ' . $dto->email . ' already exists.'
            );
        }

        $account = new User(
            $this->uuidGenerator->generate(),
            $dto->email,
        );

        $passwordBash = $this->passwordService->hashPassword($account, $dto->password);
        $account->setPassword($passwordBash);

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function getAuthorizedUser(): User
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            return $user;
        }

        throw new Exception('User was not authorized');
    }
}
