<?php


class PasswordManager
{
    public static function matchPassword($password, $confirm_pass)
    {
        return $password === $confirm_pass;
    }

    public static function checkStrength($password)
    {

        //length 8, 1 uppercase, at least 1 digit
        $pattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';

        return preg_match($pattern, $password);
    }

}