<?php


class DbErrorPage
{
    public function __construct()
    {
        require_once 'application/models/UserManager.php';
    }

    public static function noDatabaseConnection()
    {
        UserManager::logout();
        require_once 'application/views/templates/head.php';
        require_once 'application/views/errors/database_error.php';
    }
}