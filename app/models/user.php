<?php

namespace App\Model;

class User 
{
    protected $logged = null;
    protected $info = [];

    public function __construct()
    {
        session_start();
        $this->logged = isset($_SESSION['user']);
        $this->info = $_SESSION['user'] ?? [];
    }

    public function login($login, $password)
    {
        // Skip spaces
        $login = trim($login);

        // Check
        if (!$this->checkCredentials($login, $password)) return false;

        // FREE VERSION LIMITATION: login only for admin
        $this->info = $_SESSION['user'] = [
            'login' => $login,
            'isAdmin' => true
        ];
        return true;
    }

    public function getInfo()
    {
        // Get all properties
        return array_merge(['logged' => $this->logged], $this->info);
    }

    public function getProp($prop)
    {
        // Something
        return $this->info[$prop] ?? null;
    }

    public function logout()
    {
        // Forget authorization
        unset($_SESSION['user']);
        $this->info = [];
    }

    public function checkCredentials($login, $password)
    {
        // FREE VERSION LIMITATION: only one login, password
        if ($login == 'admin' && $password == '123')
            return true;

        return false;
    }

    public function isLogged()
    {
        return $this->logged == true;
    }

    public function isAdmin()
    {
        // Not logged => not admin
        if (!$this->isLogged()) return false;

        // FREE VERSION LIMITATION: all logged are admins
        return true;
    }

}
