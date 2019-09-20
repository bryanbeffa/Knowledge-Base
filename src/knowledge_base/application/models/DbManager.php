<?php

class DbManager
{

    private static $username = 'knowledge_project_admin';
    private static $password = 'KnowledgeBase2019';
    private static $db_name = 'knowledge_base_db';
    private static $host = 'localhost:3306';
    private static $conn;

    private function __construct()
    {
    }

    /**
     * Method that returns if the connection is correctly established
     */
    public static function connect()
    {
        if (!self::$conn) {

            self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);

            // set the PDO error mode to exception
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$conn;
        }
        return self::$conn;
    }
}