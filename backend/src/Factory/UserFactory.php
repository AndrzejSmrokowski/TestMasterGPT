<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public static function createUser(string $username, string $email): User
    {
        return new User($username, $email);
    }
}
