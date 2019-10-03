<?php

class Home
{
    private $user_manager;
    private $category_manager;
    private $case_manager;

    static $error_msg = "";
    static $success_msg = "";

    public function __construct()
    {
        require_once 'application/models/DbManager.php';
        require_once 'application/models/UserManager.php';
        require_once 'application/models/PasswordManager.php';
        require_once 'application/controller/dbErrorPage.php';

        try {
            $this->user_manager = new UserManager();
        } catch (PDOException $exception) {
        }
    }

    public function index()
    {
        if (isset($this->user_manager)) {
            require_once 'application/views/templates/head.php';
            require_once 'application/views/login/index.php';
        } else {
            //redirect to no connection page
            DbErrorPage::noDatabaseConnection();
        }
    }

    public static function setErrorMsg($msg)
    {
        self::$error_msg = $msg;
    }

    public static function setSuccessMsg($msg)
    {
        self::$success_msg = $msg;
    }

    /**
     * Method
     */
    public function login()
    {
        if (isset($this->user_manager)) {

            //check if the post variable are not null
            if (isset($_POST['email']) && isset($_POST['password'])) {

                //get user's inputs
                $email = $this->testInput($_POST['email']);
                $password = $this->testInput($_POST['password']);

                //check if the user credentials are correct
                if ($this->user_manager->checkCredentials($email, $password)) {

                    //save user's inputs in session variables
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $_SESSION['id'] = $this->user_manager->getIdByEmail($email);

                    //redirect to research cases - prevent re-login if the database crashed
                    header("Location: " . URL . "researchCases/showCases");

                } else {
                    echo "<div class='text-center alert alert-danger alert-dismissible fade show' role='alert'>
                  <strong>Errore!</strong> Le credenziali fornite non sono corrette
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span></button></div>";
                    $this->index();
                }
            } else {
                $this->index();
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }

    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function successMsg()
    {
        echo "<div class='text-center alert alert-success alert-dismissible fade show' role='alert' style='position:absolute; left:0;right:0; top:10%; -webkit-transform:translateY(-50%) !important; -ms-transform:translateY(-50%) !important; transform:translateY(-50%) !important;'>
                                  <strong>Ottimo!</strong>" . self::$success_msg .
            "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span></button></div>";
    }

    /**
     * Method that print the error message.
     * @param $msg message to print
     */
    public static function printError()
    {
        echo "<div class='text-center alert alert-danger alert-dismissible fade show' role='alert' style='position:absolute; left:0;right:0; top:10%; -webkit-transform:translateY(-50%) !important; -ms-transform:translateY(-50%) !important; transform:translateY(-50%) !important;'>
                                  <strong>Errore!</strong> " . self::$error_msg . "
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span></button></div>";
    }
}

