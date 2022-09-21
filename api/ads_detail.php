<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $id = $_GET['id'] ?? NULL;
        if (empty(headerToken())) {
            $dAds = getAdsDetail($id);
            if ($dAds->success) {
                response(200, "record found", $dAds->data);
            }
        } else {
            $dToken = headerAccessToken();
            if ($dToken != NULL) {
                $dAds = getAdsDetail($id);
                if ($dAds->success) {
                    response(200, "record found", $dAds->data);
                }
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>