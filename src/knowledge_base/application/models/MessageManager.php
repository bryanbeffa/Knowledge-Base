<?php


class MessageManager
{
    /**
     * @var string attribute that defines the error message.
     */
    private static $error_msg = "";

    /**
     * @var string attribute that defines the success message.
     */
    private static $success_msg = "";

    /**
     * Method that show a success alert.
     */
    public static function printSuccessMsg()
    {
        echo "<div class='text-center alert alert-success alert-dismissible fade show fixed-bottom' role='alert'>
                                  <strong>Ottimo! </strong>" . self::$success_msg . "
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
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
}