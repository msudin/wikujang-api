<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") {
        if (!empty($_GET['id'])) {
            $dProduct = getProductById($_GET['id']);
            if ($dProduct->success == true) {
                response(200, "record found", $dProduct->data);
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