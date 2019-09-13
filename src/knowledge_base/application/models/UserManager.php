<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13.09.2019
 * Time: 15:58
 */

class UserManager
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function registerUser(){
        //register new user

    }
}