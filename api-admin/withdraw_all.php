<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $status = $_GET['status'] ?? "";
        $type = $_GET['type'] ?? NULL;
        $date = $_GET['date'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dWithdraws = getAdminAllWithdraw($type, $status, $date);
            if ($dWithdraws->success) {
                response(200, "record found", $dWithdraws->data);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>