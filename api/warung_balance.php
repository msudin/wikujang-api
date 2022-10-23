<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $resultBalance = getBalance($dToken->warungId);
            if ($resultBalance->success) {
                response(200, "record found", $resultBalance->data);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>