<?php

namespace App\Framework\Authentication;

session_start();

class Auth
{
    public static $LVL1 = 1; //Organizador
    public static $LVL2 = 2; //Administrador

    public static function sessionCheck($level = 0)
    {
        if ((!isset($_SESSION['authenticated'])) || (!$_SESSION['authenticated']))
            throw new CredentialsException('Invalid Credentials');

        if ($level > $_SESSION['level'])
            throw new CredentialsException('Invalid Permissions');
    }

    public static function levelCheck($level = 0)
    {
        if ($level > $_SESSION['level'])
            return false;
        return true;
    }

    public static function createSession($level, $name, $id)
    {
        $_SESSION['authenticated'] = true;
        $_SESSION['level'] = $level;
        $_SESSION['name'] = $name;
        $_SESSION['id'] = $id;
    }

    public static function getName()
    {
        return $_SESSION['name'];
    }

    public static function getId()
    {
        return $_SESSION['id'];
    }

    public static function destroySession()
    {
        session_destroy();
    }

    public static function getAccessLevel()
    {
        return $_SESSION['level'];
    }
}