<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $resulUser = getAllUser();
        if ($resulUser->success == true) {
            response(200, "record found", $resulUser->data);
        } else {
            response(404);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>