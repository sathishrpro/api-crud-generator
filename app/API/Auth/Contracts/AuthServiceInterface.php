<?php

namespace App\API\Auth\Contracts;

interface AuthServiceInterface
{
    public function login($email, $password);
    public function register($registerData);
    public function logout();
    public function getUser();
}
