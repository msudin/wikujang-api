<?php 
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 

        // FILTER PARAM
        $name = $_GET['name'] ?? NULL;
        $limit = $_GET['limit'] ?? 0;
        $minPrice = $_GET['minPrice'] ?? 0;
        $maxPrice = $_GET['maxPrice'] ?? 1000000000;
        $categoryId = $_GET['categoryId'] ?? NULL;
        $views = $_GET['views'] ?? NULL; // asc, desc
        $price = $_GET['price'] ?? NULL; // asc, desc
        $rating = $_GET['rating'] ?? NULL; // asc, desc
        $sort = $_GET['sort'] ?? NULL; // asc, desc
        
        // FILTER FUNCTION
        $dProduct = getProductAll(
            $limit,
            null, 
            $name, 
            $minPrice, 
            $maxPrice, 
            $categoryId,
            $views, 
            $price,
            $rating,
            $sort
        );

        if ($dProduct->success) {
            response(200, "record found", $dProduct->data);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>