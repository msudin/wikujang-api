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
            $params->bookingId = $row['booking_id'];
            $params->userId = $row['user_id'];
            $params->warungId = $row['warung_id'];
            $params->debit = 0;
            $params->bankAccNumber = '';
            $params->bankAccName = '';
            $params->userName = '';
            $params->kredit = (int) ((reset($data['items']))['price']);
            $params->type = "dp_paid";
            $params->status = "";

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
        $id = uniqid();

        $sql = "INSERT INTO mutasibooking (
            `mutasi_id`,
            `booking_id`,
            `user_id`,
            `warung_id`,
            `kredit`,
            `debit`,
            `type`,
            `status`,
            `bank_acc_number`,
            `bank_acc_name`,
            `bank_user_name`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$id',
                '$body->bookingId',
                '$body->userId',
                '$body->warungId',
                $body->kredit,
                $body->debit,
                '$body->type',
                '$body->status',
                '$body->bankAccNumber',
                '$body->bankAccName',
                '$body->userName',
                '$currentDate',
                '$currentDate',
                ''
            )";
        $conn->query($sql);
        return resultBody(true);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getBalance($warungId) {
    try {
        $conn = callDb();
        
        $sql = "SELECT 
        SUM(debit) as total_debit, 
        SUM(kredit) as total_kredit 
        FROM `mutasibooking`
        WHERE warung_id = '$warungId'
        ";

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->totalDebit = (int) $row['total_debit'];
            $data->totalKredit = (int) $row['total_kredit'];
            $data->balance = $data->totalKredit - $data->totalDebit;
            return resultBody(true, $data);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}
?>