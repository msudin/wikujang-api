<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") {
        $limit = $_GET['limit'];
        $warungId = $_GET['warungId'] ?? NULL;
        $result = getProductAll($limit, $warungId);
        if ($result->success == true) {
            response(200, "record found", $result->data);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>