<?php 
include_once('../helper/import.php');

function registerCheckUserExist($phone, $password) {
    try {
        if (isNullOrEmptyString($phone)) {
            response(400, "No Hp tidak boleh kosong" );
            return true; 
        } else if (isNullOrEmptyString($password)) {
            response(400, "Password tidak boleh kosong" );
            return true; 
        } else {
            $conn = callDb();
            $sqlPhone = "SELECT * FROM `user` WHERE phone='$phone'";
            $resultPhone = $conn->query($sqlPhone);
            
            if ($resultPhone->num_rows > 0) {
                response(400, "Nomor Hp sudah terdaftar" );
                return true;
            } else {
                return false;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "user exist exception -> $error");
        return true;
    }    
}


function registerCheckAdminExist($email, $username) {
    try {
        if (isNullOrEmptyString($email)) {
            response(400, "Email tidak boleh kosong" );
            return true; 
        } else if (isNullOrEmptyString($username)) {
            response(400, "Username tidak boleh kosong" );
            return true; 
        } else {
            $conn = callDb();
            $sqlAdmin = "SELECT * FROM `admin` WHERE email='$email' OR username='$username'";
            $resultAdmin = $conn->query($sqlAdmin);
            if ($resultAdmin->num_rows > 0) {
                response(400, "Email atau Username sudah terdaftar" );
                return true;
            } else  {
                return false;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "admin exist exception -> $error");
        return true;
    }    
}

?>