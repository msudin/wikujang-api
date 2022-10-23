<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "DELETE") {
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $dToken = headerAccessToken();
            if (!empty($dToken)) {
                /// body request : 
                // 1. id booking 
                // 2. deleted = true
                $isSuccess = updateBooking($entityData);
                if ($isSuccess) {
                    response(200, "Berhasil hapus booking");
                }
            }
        } else {
            response(400);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>