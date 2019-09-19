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
        $this->conn = DbManager::connect();
    }

    public function registerUser(User $user){
        //register new user

    }

    /**
     * Method that returns if the user credentials are correct
     */
    public function checkCredentials(string $email, string $password){

        //check if the email exists
        if($this->isExistingEmail($email)) {

            //check if the password is correct
            if($this->isPasswordCorrect($email, $password)) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Method that return if the email is in the system.
     */
    private function isExistingEmail(string $email) {
        //get the number of rows that contain the user email (MAX 1)
        $prepared_query = $this->conn->prepare("SELECT count(*) FROM users WHERE email = :email");
        $prepared_query->execute(['email' => $email]);
        $res = $prepared_query->fetch();

        //check if the row count is 1
        if(intval($res[0]) == 1) {
            //email already exists
            return true;
        }

        //email do not exist
        return false;
    }

    /**
     * Method that return if the password is correct.
     */
    private function isPasswordCorrect(string $email, string $password){
        //get the password from database
        $prepared_query = $this->conn->prepare("SELECT password FROM users WHERE email = :email");
        $prepared_query->execute(['email' => $email]);
        $res = $prepared_query->fetch();

        $hashed_password = $res[0];

        //check if the inserted password is correct
        if (password_verify($password, $hashed_password)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method that returns the list of users
     */
    public function getUsersList(){
        //get the password from database
        $prepared_query = $this->conn->prepare("SELECT * FROM users");
        $prepared_query->execute();

        $users = $prepared_query->fetchAll();

        return $users;
    }

    /**
     * Method that returns if the logged user has admin privileges
     */
    public function isAdminUser($email){
        //get is_admin value
        $prepared_query = $this->conn->prepare("SELECT is_admin FROM users WHERE email = :email");
        $prepared_query->execute(['email' => $email]);

        //get result
        $res = $prepared_query->fetch();
        $is_admin = $res[0];

        if(intval($is_admin)){
            return true;
        }

        return false;
    }
}