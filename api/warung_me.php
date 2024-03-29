<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $dToken = headerAccessToken();
        if ($dToken != NULL) { 
            $resultWarung = getWarungByUserId($dToken->userId);
            if ($resultWarung->success == true) {
                response(200, "record found", $resultWarung->data);
            } else {
                response(400);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>