<?php


class Validator
{
    public function validateTextInput($text, $min_length, $max_length){
        if(strlen($text) >= $min_length && strlen($text) <= $max_length){
            return true;
        }
        return false;
    }
}