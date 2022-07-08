<?php
include_once('../helper/import.php');

try {
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        if ($entityBody != '') {
            $conn = callDb();
            $data = json_decode($entityBody, true);

            // DROP ALL DATA USER
            if (!empty($data["user"])) {
                if ($data['user'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `user`";
                    $conn->query($sqlAdmin);
                }
            }

            // DROP ALL DATA TOKEN
            if (!empty($data["token"])) {
                if ($data['token'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `token`";
                    $conn->query($sqlAdmin);
                }
            }

            // DROP ALL DATA ADDRESS
            if (!empty($data["address"])) {
                if ($data['address'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `address`";
                    $conn->query($sqlAdmin);
                }
            }
            // DROP ALL DATA FILE
            if (!empty($data["file"])) {
                if ($data['file'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `file`";
                    $conn->query($sqlAdmin);
                }
            }

            // DROP ALL DATA ADMIN
            if (!empty($data["admin"])) {
                if ($data['admin'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `admin`";
                    $conn->query($sqlAdmin);
                }
            }

            // DROP ALL DATA SUBDISTRICT
            if (!empty($data["subdistrict"])) {
                if ($data['subdistrict'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `subdistrict`";
                    $conn->query($sqlAdmin);
                }
            }

             // DROP ALL DATA DISTRICT
             if (!empty($data["district"])) {
                if ($data['district'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `district`";
                    $conn->query($sqlAdmin);
                }
            }

            // DROP ALL DATA WARUNG
            if (!empty($data["warung"])) {
                if ($data['warung'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `warung`";
                    $conn->query($sqlAdmin);
                }
            }

            // DROP ALL DATA PRODUCT
            if (!empty($data["product"])) {
                if ($data['product'] == TRUE) {
                    $sqlAdmin = "DELETE FROM `product`";
                    $conn->query($sqlAdmin);
                }
            }
        }
        response(200, "success drop data");
    } else {
        response(500, "Method not allowed");
    }
} catch(Exception $e) {
    response(500, $e->getMessage());
}

?>