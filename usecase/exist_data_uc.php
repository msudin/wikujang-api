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
        response(500, "$error");
        return true;
    }    
}

function checkUserExistByPhone($phone) {
    try {
        if (isNullOrEmptyString($phone)) {
            response(400, "No Hp tidak boleh kosong");
            return false; 
        } else {
            $conn = callDb();
            $sqlPhone = "SELECT * FROM `user` WHERE phone='$phone'";
            $resultPhone = $conn->query($sqlPhone);
            if ($resultPhone->num_rows > 0) {
                return true;
            } else {
                response(400, "No Hp belum terdaftar" );
                return false;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
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
        response(500, "$error");
        return true;
    }    
}

function checkCategoryExistByName($categoryName) {
    try {
        if (isNullOrEmptyString($categoryName)) {
            response(400, "Nama category mandatory");
            return false; 
        } else {
            $conn = callDb();
            $sql = "SELECT * FROM `category` WHERE `category_name` = '$categoryName'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                response(400, "Kategori sudah ada");
                return false;
            } else {
                return true;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }    
}

function validateWarungExist($userId, $username) {
    try {
        if (isNullOrEmptyString($username)) {
            response(400, "Username warung tidak boleh kosong" );
            return true;
        } else {
            $conn = callDb();
            $sql = "SELECT COUNT(warung_id) AS TotalWarung 
                FROM warung WHERE `user_id`=$userId 
                AND deleted_at=''";

            $resultSql = $conn->query($sql);
            while($rowData = $resultSql->fetch_assoc()) {
                $totalData = (int) $rowData['TotalWarung'];
                if ($totalData == 0) {
                    $sqlWarung = "SELECT COUNT(warung_id) AS TotalWarung FROM warung 
                        WHERE `username`='$username' 
                        AND deleted_at=''";

                    $result = $conn->query($sqlWarung);
                    while($row = $result->fetch_assoc()) {
                        $total = (int) $row['TotalWarung'];
                        if ($total > 0) {
                           response(400, "Username sudah terdaftar, silahkan gunakan username lain" );
                           return false;
                        } else {
                           return false;
                        }

                        return false;
                    }
                } else {
                  response(400, "Akun sudah memiliki warung");
                  return true;
                }
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
        return true;
    }
}
?>