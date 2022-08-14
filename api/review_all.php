<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $productId = $_GET['productId'] ?? NULL;
        $resultReview = getAllReviewByProductId($productId);
        if ($resultReview->success == true) {
            response(200, "record found", $resultReview->data);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>