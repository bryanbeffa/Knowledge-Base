<?php


class PasswordManager
{
    public static function matchPassword($password, $confirm_pass){
        if($password === $confirm_pass){
            return true;
        }
        return false;
    }

}