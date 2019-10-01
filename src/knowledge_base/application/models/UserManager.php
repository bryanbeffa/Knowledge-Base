<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13.09.2019
 * Time: 15:58
 */

class UserManager
{
    private $conn;

    public function __construct()
    {
        require_once 'application/controller/home.php';
        try {
            $this->conn = DbManager::connect();
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * Method that returns if the user credentials are correct
     */
    public function checkCredentials(string $email, string $password)
    {
        //check if the email exists
        if ($this->isExistingEmail($email)) {

            //check if the password is correct
            if ($this->isPasswordCorrect($email, $password)) {
                return true;
            }

            return false;
        }

        return false;

    }

    /**
     * Method that return if the email is in the system.
     */
    private function isExistingEmail(string $email)
    {
        //get the number of rows that contain the user email (MAX 1)
        $prepared_query = $this->conn->prepare("SELECT count(*) FROM users WHERE email = :email");
        $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
        $prepared_query->execute();
        $res = $prepared_query->fetch();

        //check if the row count is 1
        if (intval($res[0]) == 1) {
            //email already exists
            return true;
        }

        //email do not exist
        return false;
    }

    /**
     * Method that return if the password is correct.
     */
    private function isPasswordCorrect(string $email, string $password)
    {
        //get the password from database
        $prepared_query = $this->conn->prepare("SELECT password FROM users WHERE email = :email");
        $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
        $prepared_query->execute();
        $res = $prepared_query->fetch();

        $hashed_password = $res[0];

        //check if the inserted password is correct
        if (password_verify($password, $hashed_password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method that returns the list of users
     */
    public function getUsersList()
    {
        //get the password from database
        $prepared_query = $this->conn->prepare("SELECT * FROM users");
        $prepared_query->execute();

        $users = $prepared_query->fetchAll();

        return $users;
    }

    /**
     * Method that returns if the logged user has admin privileges
     */
    public function isAdminUser($email)
    {
        //get is_admin value
        $prepared_query = $this->conn->prepare("SELECT is_admin FROM users WHERE email = :email");
        $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
        $prepared_query->execute();

        //get result
        $res = $prepared_query->fetch();
        $is_admin = $res[0];

        if (intval($is_admin)) {
            return true;
        }

        return false;
    }

    /**
     * Method that try to create a new user.
     * @param User $user user will be created
     * @return bool if the user has been created.
     */
    public function createUser(User $user)
    {
        //check if the email already exists
        if (!$this->isExistingEmail($user->getEmail())) {

            try {
                //prepare query
                $prepared_query = $this->conn->prepare("INSERT INTO USERS (name, surname, email, password, is_admin, change_pass) VALUES(:name, :surname, :email, :password, :is_admin, :change_pass)");

                //get params
                $name = $user->getName();
                $surname = $user->getSurname();
                $email = $user->getEmail();
                $password = $user->getPassword();
                $is_admin = $user->getAdmin();
                $change_pass = $user->getChangePass();

                //bind params
                $prepared_query->bindParam(':name', $name, PDO::PARAM_STR);
                $prepared_query->bindParam(':surname', $surname, PDO::PARAM_STR);
                $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
                $prepared_query->bindParam(':password', $password, PDO::PARAM_STR);
                $prepared_query->bindParam(':is_admin', $is_admin, PDO::PARAM_INT);
                $prepared_query->bindParam(':change_pass', $change_pass, PDO::PARAM_INT);

                $prepared_query->execute();

                return true;

            } catch (PDOException $ex) {
                Home::setErrorMsg("Impossibile creare l'utente");
                return false;
            }
        } else {
            Home::setErrorMsg("Email già utilizzata");
            return false;
        }
    }

    /**
     * Method that sets the db field 'change_pass' of the table users to the specified user to 1.
     * @param $id id of the user who requested the change password.
     */
    public function requestChangePass($id){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("UPDATE USERS SET change_pass = 1 WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

        } catch (PDOException $ex) {
        }
    }

    /**
     * Method that tries to delete the user.
     * @param $id id of the user who will be deleted
     */
    public function deleteUser($id){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("DELETE FROM USERS WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

        } catch (PDOException $ex) {
        }
    }

    /**
     * Get user id by email.
     * @param $email user email
     */
    public function getIdByEmail($email){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("SELECT id FROM USERS WHERE EMAIL = :email");
            $prepared_query->bindParam(':email', $email, PDO::PARAM_STR);
            $prepared_query->execute();

            $res = $prepared_query->fetch();
            return $res[0];

        } catch (PDOException $ex) {
        }
    }
}