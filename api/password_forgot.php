<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") { 
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        if (!empty($entityBody) && !empty($entityData["newPassword"])) { 
            $isSuccess = forgotPassword($entityData);
            if ($isSuccess) {
                response(200, "Berhasil ubah password");
            }
        }
    } else {
        response(500);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    response(500, $error);
}
?>