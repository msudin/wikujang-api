<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $status = $_GET['status'] ?? NULL;
        $limit = $_GET['limit'] ?? NULL;
        $typeAccess = $_GET['type_access'] ?? "me";
        $paymentStatus = $_GET['paymentStatus'] ?? NULL;
        $bookingDate = $_GET['bookingDate'] ?? NULL;

        if (empty(headerToken())) {
            $dBookings = getBookingAll($status, $limit, $paymentStatus);
            if ($dBookings->success) {
                response(200, "record found", $dBookings->data);
            }
        } else {
            $dToken = headerAccessToken();
            if ($dToken != NULL) {
                if ($typeAccess == "warung") {
                    $dBookings = getBookingAll($status, $limit, $paymentStatus, $dToken->warungId, NULL, $bookingDate);
                } else {
                    $dBookings = getBookingAll($status, $limit, $paymentStatus, NULL, $dToken->userId, $bookingDate);
                }
                if ($dBookings->success) {
                    response(200, "record found", $dBookings->data);
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