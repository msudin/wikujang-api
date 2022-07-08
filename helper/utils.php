<?php 

function isNullOrEmptyString($str){
    return ($str === NULL || trim($str) === '');
}

function generateCustomHash($lenght = 32) {
    return bin2hex(openssl_random_pseudo_bytes($lenght));
}

?>