<?php 
include_once('../helper/import.php');

try {
    if (requestMethod() == "GET") { 
        $resultData = getAllCategory();
        if ($resultData->success) {
            response(200, "record found", $resultData->data);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>