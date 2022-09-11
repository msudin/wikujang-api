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
                // 1. id ads 
                // 2. deleted = true
                $isSuccess = updateAds($entityData);
                if ($isSuccess) {
                    response(200, "Berhasil hapus iklan");
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