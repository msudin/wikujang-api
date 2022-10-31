<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $dToken = headerAccessToken();
        $date = $_GET['date'] ?? NULL;
        if ($dToken != NULL) {
            $dListBalance = getListBalance($dToken->warungId, $date);
            if ($dListBalance->success) {
                response(200, "record found", $dListBalance->data);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>