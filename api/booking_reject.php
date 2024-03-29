<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $dToken = headerAccessToken();
            if ($dToken != NULL) {
                $bodyRequest = array(
                   "id" => $data['id'],
                   "status" => "rejected",
                   "reason" => $data['reason']
                );
                $dUpdateBooking = updateBooking($bodyRequest);
                if ($dUpdateBooking) {
                    response(200, "Booking ditolak");
                }
            } 
        } else {
            response(400);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>