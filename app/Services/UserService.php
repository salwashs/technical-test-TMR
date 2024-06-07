<?php

namespace App\Services;

use App\Models\User;

interface UserService
{
    function login(string $email, string $password): bool;

    function register(string $name, string $email, string $password): bool;
}
