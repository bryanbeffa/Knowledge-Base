<?php


class Home
{

    public function __construct()
    {
        require_once 'application/models/DbManager';
    }

    public function index()
    {

        require_once 'application/views/templates/header.php';
        require_once 'application/views/login/index.php';

        //try to connect to database
        try {
            $conn = DbManager::connect();
            echo 'Connessione riuscita';
        } catch (PDOException $e) {
            echo 'Errore di connessione al database';
        }

        require_once 'application/views/templates/footer.php';
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

