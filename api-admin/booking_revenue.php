
<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $date = $_GET['paymentDate'] ?? "";
        $paymentStatus = $_GET['paymentStatus'] ?? NULL;
        $status = $_GET['status'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $booking = bookingRevenue($date, $paymentStatus);
            if ($booking->success) {
                response(200, "record found", $booking->data);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>