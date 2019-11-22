<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13.09.2019
 * Time: 15:23
 */

class User
{
    /**
     * attribute that identifies the user name
     */
    private $name;

    /**
     * attribute that identifies the user surname
     */
    private $surname;

    /**
     * attribute that identifies the user email
     */
    private $email;

    /**
     * attribute that identifies the user password
     */
    private $password;

    /**
     * attribute that identifies if the user has admin privileges
     */
    private $admin;

    /**
     * attribute that identifies if there is a change password request (feature)
     */
    private $change_pass;

    public function __construct(string $name, string $surname, string $email, string $password, $admin, $change_pass )
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->admin = $admin;
        $this->change_pass = $change_pass;
    }

    /**
     * Method that returns the password
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Method that returns the name
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Method that returns the surname
     */
    public function getSurname(){
        return $this->surname;
    }

    /**
     * Method that returns the email
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Method that returns the admin
     */
    public function getAdmin(){
        return $this->admin;
    }

    /**
     * Method that returns the email
     */
    public function getChangePass(){
        return $this->change_pass;
    }
}