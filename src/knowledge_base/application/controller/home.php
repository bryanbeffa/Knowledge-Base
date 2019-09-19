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

    }

    /**
     * Method
     */
    public function login()
    {

        //get user's inputs
        $email = $this->testInput($_POST['email']);
        $password = $this->testInput($_POST['password']);

        if ($this->user_manager->checkCredentials($email, $password)) {

            //save user's inputs in session variables
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            $this->researchCases();

        } else {
            $this->index();
        }
    }

    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function manageUsers()
    {
        //check if the user is logged
        if ($this->isUserLogged()) {
            //check if the user is an admin

            if ($this->isAdmin()) {

                //get users list
                $users = $this->user_manager->getUsersList();

                require_once 'application/views/templates/head.php';
                require_once 'application/views/templates/admin_header.php';
                require_once 'application/views/admin/gestione_utenti.php';
            } else {

                //redirect to cases page
                $this->researchCases();
            }
        } else {
            //redirect to login page
            $this->index();
        }
    }

    public function researchCases()
    {
        //check if the uses is logged
        if ($this->isUserLogged()) {

            require_once 'application/views/templates/head.php';

            //check if the user is an admin
            if ($this->isAdmin()) {
                require_once 'application/views/templates/admin_header.php';
            } else {
                require_once 'application/views/templates/user_header.php';
            }
            require_once 'application/views/users/ricerca_casi.php';

        } else {
            //redirect to login page
            $this->index();
        }
    }

    /**
     * Method that returns if the logged user has admin privileges
     */
    private function isAdmin()
    {

        if ($this->user_manager->isAdminUser($_SESSION['email'])) {
            return true;
        }

        return false;
    }

    /**
     * Method that unsets session variables
     */
    public function logout()
    {
        $_SESSION['email'] = '';
        $_SESSION['password'] = '';

        //redirect to login page
        $this->index();
    }

    /**
     * Method that returns if the user is logged
     */
    private function isUserLogged()
    {
        if (isset($_SESSION['email']) && isset($_SESSION['email'])) {
            if ($this->user_manager->checkCredentials($_SESSION['email'], $_SESSION['password'])) {
                return true;
            }
        }
        return false;
    }
}

