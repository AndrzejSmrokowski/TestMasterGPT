<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $attribute, string $value)
    {
        parent::__construct(sprintf('User with %s %s already exists!', $attribute, $value));
    }
}