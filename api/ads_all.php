<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $status = $_GET['status'] ?? NULL;
        $limit = $_GET['limit'] ?? NULL;
        $paymentStatus = $_GET['paymentStatus'] ?? NULL;
        if (empty(headerToken())) {
            $dAds = getAdsAll($status, $limit, $paymentStatus);
            if ($dAds->success) {
                response(200, "record found", $dAds->data);
            }
        } else {
            $dToken = headerAccessToken();
            if ($dToken != NULL) {
                $dAds = getAdsAll($status, $limit, $paymentStatus, $dToken->warungId);
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