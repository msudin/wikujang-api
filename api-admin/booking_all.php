<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $status = $_GET['status'] ?? NULL;
        $limit = $_GET['limit'] ?? NULL;
        $paymentStatus = $_GET['paymentStatus'] ?? NULL;
        
        $dBookings = getBookingAll($status, $limit, $paymentStatus);
        if ($dBookings->success) {
            response(200, "record found", $dBookings->data);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>