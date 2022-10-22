<?php
include_once('../helper/import.php');

function webhookBookingHandler($data) {
    try {
        $conn = callDb();
        $invoiceId = $data['id'];

        $sql = "SELECT 
        booking_id, 
        user_id, 
        warung_id
        FROM `booking` 
        WHERE invoice_id = '$invoiceId'";

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $params = new stdClass();
            $params->mutasiId = uniqid();
            $params->bookingId = $row['booking_id'];
            $params->userId = $row['user_id'];
            $params->warungId = $row['warung_id'];
            $params->debit = 0;
            $params->kredit = (int) ((reset($data['items']))['price']);
            $params->status = "dp_paid";

            $dRequest = createMutasiBooking($params);
            if ($dRequest->success) {
                return $dRequest;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function createMutasiBooking($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "INSERT INTO mutasibooking (
            `mutasi_id`,
            `booking_id`,
            `user_id`,
            `warung_id`,
            `kredit`,
            `debit`,
            `status`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$body->mutasiId',
                '$body->bookingId',
                '$body->userId',
                '$body->warungId',
                $body->kredit,
                $body->debit,
                '$body->status',
                '$currentDate',
                '$currentDate',
                ''
            )";
        // $conn->query($sql);
        return resultBody(true);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}
?>