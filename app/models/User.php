<?php

namespace app\models;

class User 
{
    private $isLogged;
    protected $info = [];

    public function __construct()
    {
        session_start();

        $this->isLogged = isset($_SESSION['user']);
        $this->info     = $_SESSION['user'] ?? [];
    }

    public function login($login, $password): bool
    {
        // Skip spaces
        $login = trim($login);

        // Check
        if (!$this->checkCredentials($login, $password)) {
            return false;
        }

        // FREE VERSION LIMITATION: login only for admin
        $this->info = $_SESSION['user'] = [
            'login' => $login,
            'isAdmin' => true
        ];

        return true;
    }

    public function getInfo(): array
    {
        // Get all properties
        return array_merge(['logged' => $this->isLogged], $this->info);
    }

    public function logout()
    {
        // Forget authorization
        unset($_SESSION['user']);
        $this->info = [];
    }

    public function checkCredentials($login, $password): bool
    {
        // FREE VERSION LIMITATION: only one login, password
        return $login === 'admin' && $password === '123';
    }

    public function isLogged(): bool
    {
        return $this->isLogged;
    }

    public function isAdmin(): bool
    {
        // Not logged => not admin
        if (!$this->isLogged()) {
            return false;
        }

        // FREE VERSION LIMITATION: all logged are admins
        return true;
    }
}
