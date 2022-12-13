<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $id = $_GET['id'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dUpdateMutasi = updateMutasiStatus($id, "transfered");
            if ($dUpdateMutasi->success) {
                response(200, "Withdraw berhasil dan dana sudah di transfer", NULL);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>