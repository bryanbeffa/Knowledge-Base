<?php


class DbErrorPage
{
    public function __construct()
    {
        require_once 'application/models/UserManager.php';
    }

    /**
     * Method that shows the error page if cannot connect to db.
     */
    public static function noDatabaseConnection()
    {
        UserManager::logout();
        require_once 'application/views/templates/head.php';
        require_once 'application/views/errors/database_error.php';
    }
}