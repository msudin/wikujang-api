<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $bookingDate = $_GET['bookingDate'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dSummary = summaryBooking($bookingDate);
            response(200, "record found", $dSummary->data);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>