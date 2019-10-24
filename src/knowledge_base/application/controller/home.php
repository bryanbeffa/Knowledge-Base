<?php

class Home
{
    /**
     * @var UserManager attributes that defines the user manager object.
     */
    private $user_manager;

    /**
     * @var string attribute that defines the error message.
     */
    private static $error_msg = "";

    /**
     * @var string attribute that defines the success message.
     */
    private static $success_msg = "";

    public function __construct()
    {
        require_once 'application/models/DbManager.php';
        require_once 'application/models/UserManager.php';
        require_once 'application/models/PasswordManager.php';
        require_once 'application/controller/dbError.php';

        try {
            $this->user_manager = new UserManager();
        } catch (PDOException $exception) {
        }
    }

    /**
     * Method that shows the login page.
     */
    public function index()
    {
        if (isset($this->user_manager)) {
            require_once 'application/views/templates/head.php';
            require_once 'application/views/login/index.php';
        } else {
            //redirect to no connection page
            DbError::noDatabaseConnection();
        }
    }

    /**
     * @param $msg error message to set.
     */
    public static function setErrorMsg($msg)
    {
        self::$error_msg = $msg;
    }

    /**
     * @param $msg success message to set.
     */
    public static function setSuccessMsg($msg)
    {
        self::$success_msg = $msg;
    }

    /**
     * Method that checks if the credentials are correct.
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
                    $_SESSION['id'] = intval($this->user_manager->getIdByEmail($email));

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
            DbError::noDatabaseConnection();
        }
    }

    /**
     * Method that validates the param
     * @param $data data to validate
     * @return string validated data
     */
    private function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Method that show a success alert.
     */
    public static function printSuccessMsg()
    {
        echo "<div class='text-center alert alert-success alert-dismissible fade show fixed-bottom' role='alert'>
                                  <strong>Ottimo! </strong>" . self::$success_msg .
            "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span></button></div>";
    }

    /**
     * Method that print the error message.
     */
    public static function printErrorMsg()
    {
        echo "<div class='text-center alert alert-danger alert-dismissible fade show fixed-bottom' role='alert'>
                                  <strong>Errore! </strong>" . self::$error_msg . "
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                  <span aria-hidden='true'>&times;</span></button></div>";
    }
}

