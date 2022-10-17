<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $id = $_GET['id'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dBooking = getBookingDetail($id);
            if ($dBooking->success) {
                response(200, "record found", $dBooking->data);
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>