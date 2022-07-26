<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "DELETE") {
        $headerToken = headerToken();
        $dToken = validateToken($headerToken);
        $entityData = ['deleted' => true];
        if (!empty($dToken)) {
            $isSuccess = updateWarung($entityData, $dToken->warungId);
            if ($isSuccess) {
                response(200, "Berhasil hapus warung");
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>