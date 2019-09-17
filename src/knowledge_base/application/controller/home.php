<?php


class Home
{
    private static $conn;
    private $user_manager;

    public function __construct()
    {
        require_once 'application/models/DbManager.php';
        require_once 'application/models/UserManager.php';
        require_once 'application/models/User.php';

        $this->user_manager = new UserManager();
    }

    public function index()
    {
        require_once 'application/views/templates/head.php';
        require_once 'application/views/login/index.php';

        //testing connection to db
        try {
            self::$conn = DbManager::connect();
            //echo "Connessione riuscita";
        } catch (PDOException $e) {
            //echo "Connessione fallita";
        }

        $this->login();
    }

    /**
     * Method
     */
    public function login(){
        if ($this->user_manager->checkCredentials("ciao@ciao.ciao", 'cane')){
            echo "Login effettutato correttamente";
        } else {
            echo "Username o password sbagliate";
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

