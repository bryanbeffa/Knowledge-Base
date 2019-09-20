<?php

class Home
{
    private $user_manager;

    public function __construct()
    {
        require_once 'application/models/DbManager.php';
        require_once 'application/models/UserManager.php';
        require_once 'application/models/User.php';
        require_once 'application/models/PasswordManager.php';

        $this->user_manager = new UserManager();
    }

    public function index()
    {
        require_once 'application/views/templates/head.php';
        require_once 'application/views/login/index.php';
    }

    /**
     * Method
     */
    public function login()
    {

        //get user's inputs
        $email = $this->testInput($_POST['email']);
        $password = $this->testInput($_POST['password']);

        //check if the user credentials are correct
        if ($this->user_manager->checkCredentials($email, $password)) {

            //save user's inputs in session variables
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            $this->researchCases();

        } else {
            echo "<div class='text-center alert alert-danger alert-dismissible fade show' role='alert'>
                  <strong>Errore!</strong> Le credenziali fornite non sono corrette
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span></button></div>";
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

    public function createUser()
    {
        //check if the uses is logged
        if ($this->isUserLogged()) {
            require_once 'application/views/templates/head.php';

            $password = $this->testInput($_POST['password']);
            $confirm_pass = $this->testInput($_POST['confirm_pass']);

            //check if the password are the same
            if (PasswordManager::matchPassword($password, $confirm_pass)) {

                //check if the user is an admin
                if ($this->isAdmin()) {
                    //create user
                    $name = $this->testInput($_POST['name']);
                    $surname = $this->testInput($_POST['surname']);
                    $email = $this->testInput($_POST['email']);
                    $is_admin = $this->testInput($_POST['is_admin']);

                    //hash password
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    $user = new User($name, $surname, $email, $password, $is_admin, 0);

                    if ($this->user_manager->createUser($user)) {
                        echo "<div class='text-center alert alert-success alert-dismissible fade show' role='alert'>
                                  <strong>Ottimo!</strong> L'utente è stato creato con successo 
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span></button></div>";
                    } else {
                        $this->printError("Impossibile creare l'utente");
                    }

                    $this->manageUsers();

                } else {
                    require_once 'application/views/templates/user_header.php';
                    require_once 'application/views/users/ricerca_casi.php';
                }
            } else{
                //redirect to manager page
                $this->printError("Le due password non corrispondono");
                $this->manageUsers();
            }
        } else {
            //redirect to login page
            $this->index();
        }
    }

    /**
     * Method that print the error message.
     * @param $msg message to print
     */
    private function printError($msg){
        echo "<div class='text-center alert alert-danger alert-dismissible fade show' role='alert' style='position:absolute; left:0;right:0; top:10%; -webkit-transform:translateY(-50%) !important; -ms-transform:translateY(-50%) !important; transform:translateY(-50%) !important;'>
                                  <strong>Errore!</strong> $msg 
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span></button></div>";
    }
}

