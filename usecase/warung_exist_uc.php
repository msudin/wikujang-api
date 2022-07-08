<?php
include_once('../helper/import.php');

function validateWarungExist($userId, $username) {
    try {
        if (isNullOrEmptyString($username)) {
            response(400, "Username warung tidak boleh kosong" );
            return true;
        } else {
            $conn = callDb();
            $sql = "SELECT COUNT(warung_id) AS TotalWarung FROM warung WHERE `user_id`=$userId";
            $resultSql = $conn->query($sql);
            while($rowData = $resultSql->fetch_assoc()) {
                $totalData = (int) $rowData['TotalWarung'];
                if ($totalData == 0) {
                    $sqlWarung = "SELECT COUNT(warung_id) AS TotalWarung FROM warung WHERE `username`='$username'";
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
        response(500, "Validate username warung -> $error");
        return true;
    }
}
?>