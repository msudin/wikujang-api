<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $status = $_GET['status'] ?? NULL;
            $limit = $_GET['limit'] ?? NULL;
            $dAds = getAdsAll($status, $limit);
            if ($dAds->success) {
                response(200, "record found", $dAds->data);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>