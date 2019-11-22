<?php


class PasswordManager
{
    /**
     * Method that return if the passwords match
     * @param $password user password
     * @param $confirm_pass confirm password
     * @return bool if the passwords match
     */
    public static function matchPassword($password, $confirm_pass)
    {
        return $password === $confirm_pass;
    }

    /**
     * Method that returns if the password is complex
     * @param $password password to check
     * @return bool if the password is complex enough
     */
    public static function checkStrength($password)
    {

        //length 8, 1 uppercase, at least 1 digit, max length 50 characters
        $pattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,50}$/';

        return preg_match($pattern, $password);
    }

}