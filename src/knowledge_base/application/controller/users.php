<?php

class Users
{

    private $user_manager;

    public function __construct()
    {
        require_once 'application/models/DbManager.php';
        require_once 'application/models/UserManager.php';
        require_once 'application/models/PasswordManager.php';
        require_once 'application/models/User.php';
        require_once 'application/controller/dbErrorPage.php';

        try {
            $this->user_manager = new UserManager();
        } catch (PDOException $ex) {
        }
    }

    public static function logout()
    {
        UserManager::logout();

        //redirect to login page
        header("Location: " . URL . "home/index");
    }

    public function manageUsers()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {
            //check if the user is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    //get users list
                    $users = $this->user_manager->getUsersList();

                    require_once 'application/views/templates/head.php';
                    require_once 'application/views/templates/admin_header.php';
                    require_once 'application/views/admin/gestione_utenti.php';
                } else {

                    //redirect to cases page
                    header("Location: " . URL . "researchCases/showCases");
                }
            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }

    /**
     * Method that tries to delete the user.
     * @param $id id of the user who will be deleted
     */
    public function deleteUser($id)
    {
        //check if the db is connected
        if (isset($this->user_manager)) {
            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    //try to delete user
                    $this->user_manager->deleteUSer($id);

                    //redirect manage users page
                    header('Location: ' . URL . "users/manageUsers");

                } else {
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }

    public function createUser()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {
                require_once 'application/views/templates/head.php';

                //check if the post variable are not null
                if (isset($_POST['password']) && isset($_POST['confirm_pass'])
                    && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['is_admin'])) {


                    $password = $this->testInput($_POST['password']);
                    $confirm_pass = $this->testInput($_POST['confirm_pass']);

                    //check if the password are the same
                    if (PasswordManager::matchPassword($password, $confirm_pass)) {

                        //check if the user is an admin
                        if (UserManager::isAdminUser($_SESSION['email'])) {
                            //create user
                            $name = $this->testInput($_POST['name']);
                            $surname = $this->testInput($_POST['surname']);
                            $email = $this->testInput($_POST['email']);
                            $is_admin = $this->testInput($_POST['is_admin']);

                            //save data into session variables
                            $_SESSION['new_user_name'] = $name;
                            $_SESSION['new_user_surname'] = $surname;
                            $_SESSION['new_user_email'] = $email;

                            //check if the text fields are not empty
                            if (!empty($name) && !empty($password) && !empty($surname)) {
                                //hash password
                                $password = password_hash($password, PASSWORD_DEFAULT);

                                $user = new User($name, $surname, $email, $password, $is_admin, 0);

                                if ($this->user_manager->createUser($user)) {
                                    //user created message
                                    Home::setSuccessMsg("L'utente è stato creato con successo ");
                                    Home::successMsg();

                                    unset($_SESSION['new_user_name']);
                                    unset($_SESSION['new_user_surname']);
                                    unset($_SESSION['new_user_email']);
                                } else {
                                    Home::printError();
                                }
                            } else {
                                Home::$error_msg = "I campi di testo non possono essere vuoti";
                                Home::printError();
                            }


                            $this->manageUsers();

                        } else {
                            $this->researchCases();
                        }
                    } else {
                        //redirect to manager page
                        Home::setErrorMsg("Le password non corrispondono");
                        Home::printError();
                        $this->manageUsers();
                    }
                } else {
                    $this->manageUsers();
                }
            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
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

    /**
     * Method that set the user field 'change_pass' to 1. User has requested a password change.
     * @param $id user id
     */
    public function requestChangePass($id)
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    //set change_pass
                    $this->user_manager->requestChangePass($id);

                    //redirect manage users page
                    header('Location: ' . URL . "users/manageUsers");

                } else {
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }
}