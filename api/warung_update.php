<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $headerToken = headerToken();
            $dToken = validateToken($headerToken);
            if (!empty($dToken)) {
                $isSuccess = updateWarung($entityData, $dToken->warungId);
                if ($isSuccess) {
                    response(200, "Berhasil update warung");
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