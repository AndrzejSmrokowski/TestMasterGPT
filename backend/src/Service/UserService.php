<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserService
{
    private EntityManagerInterface $entityManager;
    private UserFactory $userFactory;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserFactory $userFactory,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->userFactory = $userFactory;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function createUser(array $data): User
    {
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            throw new UserAlreadyExistsException('email', $data['email']);
        }

        $user = $this->userFactory->createUser($data['username'], $data['email']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setpassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getUserById(int $id): User
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw new UserNotFoundException('User not found!');
        }

        return $user;
    }

}