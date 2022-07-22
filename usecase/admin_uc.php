<?php 
include_once('../helper/import.php');

function createAdmin($bodyRequest) {
    try {
        $conn = callDb();
        $sql = "INSERT INTO `admin` (
            `fullname`,
            `username`,
            `email`, 
            `password`
            ) VALUES (
                 '$bodyRequest->fullName',
                 '$bodyRequest->userName', 
                 '$bodyRequest->email', 
                 '$bodyRequest->password'
            )";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
        return false;
    }
}

?>