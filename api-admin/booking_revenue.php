
<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $date = $_GET['paymentDate'] ?? "";
        $paymentStatus = $_GET['paymentStatus'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dAds = bookingRevenue($date, $paymentStatus);
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