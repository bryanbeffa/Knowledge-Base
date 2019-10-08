<?php


class PasswordManager
{
    public static function matchPassword($password, $confirm_pass)
    {
        if ($password === $confirm_pass) {
            return true;
        }
        return false;
    }

    public static function checkStrength($password)
    {

        //length 8, 1 uppercase, at least 1 digit
        $pattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';

        if (preg_match($pattern, $password)) {
            return true;
        }

        return false;
    }

}