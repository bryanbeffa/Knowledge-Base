<?php

class DbManager
{

    private static $username = 'knowledge_project_admin';
    private static $password = 'KnowledgeBase2019';
    private static $db_name = 'knowledge_base_db';
    private static $host = 'localhost:8080';
    private static $conn;

    private function __construct()
    {
    }

    /**
     * Method that returns if the connection is correctly established
     */
    public function connect()
    {
        if (!self::$conn) {
            try {
                self::$conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);

                // set the PDO error mode to exception
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return self::$conn;

            } catch (PDOException $e) {
                PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$conn;
    }

}