<?php


class Validator
{
    /**
     * Method that return if the text is valid
     * @param $text text to test
     * @param $min_length min text length
     * @param $max_length max text length
     * @return bool if the text is valid
     */
    public function validateTextInput($text, $min_length, $max_length){
        return (strlen($text) >= $min_length && strlen($text) <= $max_length);
    }
}