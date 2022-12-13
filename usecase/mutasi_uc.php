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

            $dRequest = createMutasi($params);
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

function createMutasi($body) {
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
        WHERE warung_id = '$warungId'";

        $result = $conn->query($sql);
        $data = new stdClass();
        $data->totalDebit = 0;
        $data->totalCredit = 0;
        $data->totalBalance = 0;

        while($row = $result->fetch_assoc()) {
            $data->totalDebit = (int) $row['total_debit'];
            $data->totalCredit = (int) $row['total_kredit'];
            $data->totalBalance = (int) ($data->totalCredit - $data->totalDebit) ?? 0;
        }
        return resultBody(true, $data);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}


function getListBalance(
    $warungId,
    $date = NULL
    ) {
        try {
            $conn = callDb();
            $array = array();
            
            $sql = "SELECT * 
            FROM `mutasibooking`
            WHERE warung_id = '$warungId'";

            if (!empty($date)) {
                $sql = $sql." AND `created_at` LIKE '%$date%'";
            }

            $sql = $sql." ORDER BY created_at DESC";

            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['mutasi_id'] ?? "";
                $data->bookingId = $row['booking_id'] ?? "";
                $data->userId = (int) $row['user_id'] ?? "0";
                $data->warungId = $row['warung_id'] ?? "";
                $data->credit = (int) $row['kredit'] ?? "0";
                $data->debit = (int) $row['debit'] ?? "0";
                $data->type = $row['type'] ?? "";
                $data->status = $row['status'] ?? "";
                $data->bankAccNumber = $row['bank_acc_number'] ?? "";
                $data->bankAccName = $row['bank_acc_name'] ?? "";
                $data->bankUserName = $row['bank_user_name'] ?? "";
                $data->createdAt = $row['created_at'] ?? "";
                $data->updatedAt = $row['updated_at'] ?? "";
                $data->deletedAt = $row['deleted_at'] ?? "";
                array_push($array, $data);
            }
            return resultBody(true, $array);
        } catch (Exception $e) {
            $error = $e->getMessage();
            response(500, $error);
            return resultBody();
        }
}


function getMutasiById($mutasiId) {
        try {
            $conn = callDb();
            $array = array();
            
            $sql = "SELECT * 
            FROM `mutasibooking`
            WHERE `mutasi_id` = '$mutasiId'";

            $result = $conn->query($sql);
            $data = new stdClass();
            while($row = $result->fetch_assoc()) {
                $data->id = $row['mutasi_id'] ?? "";
                $data->bookingId = $row['booking_id'] ?? "";
                $data->userId = (int) $row['user_id'] ?? "0";
                $data->warungId = $row['warung_id'] ?? "";
                $data->credit = (int) $row['kredit'] ?? "0";
                $data->debit = (int) $row['debit'] ?? "0";
                $data->type = $row['type'] ?? "";
                $data->status = $row['status'] ?? "";
                $data->bankAccNumber = $row['bank_acc_number'] ?? "";
                $data->bankAccName = $row['bank_acc_name'] ?? "";
                $data->bankUserName = $row['bank_user_name'] ?? "";
                $data->createdAt = $row['created_at'] ?? "";
                $data->updatedAt = $row['updated_at'] ?? "";
                $data->deletedAt = $row['deleted_at'] ?? "";
            }
            return resultBody(true, $data);
        } catch (Exception $e) {
            $error = $e->getMessage();
            response(500, $error);
            return resultBody();
        }
}

function updateMutasiStatus($mutasiId, $status) {
    try {
        $conn = callDb();
        $updatedAt = currentTime();
        $sql = "UPDATE mutasibooking SET
            `status`= '$status',
            `updated_at` = '$updatedAt'
        WHERE `mutasi_id`= '$mutasiId'";
        $conn->query($sql);
        return resultBody(true, NULL);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getAdminAllWithdraw(
    $type = NULL,
    $status = NULL,
    $date = NULL
    ) {
        try {
            $conn = callDb();
            $array = array();
            
            $sql = "SELECT
            w.name as warung_name,
            u.fullname as user_fullname,
            u.phone as user_phone,
            m.*
            FROM `mutasibooking` m
            LEFT JOIN `warung` w ON m.warung_id = w.warung_id
            LEFT JOIN `user` u ON m.user_id = u.user_id";

            if (empty($type)) {
                $sql = $sql." WHERE NOT type = 'dp_paid'";    
            } else {
                $sql = $sql." WHERE `type` = '$type'";    
            }

            $sql = $sql." AND `status` = '$status'";

            if (!empty($date)) {
                $sql = $sql." AND created_at LIKE '%$date%'";
            }

            $sql = $sql." ORDER BY m.created_at DESC";

            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['mutasi_id'] ?? "";
                $data->bookingId = $row['booking_id'] ?? "";
                $data->warung = NULL;
                if (!empty($row['warung_id'])) {
                    $warung = new stdClass();
                    $warung->id = $row['warung_id'];
                    $warung->name = $row['warung_name'];
                    $data->warung = $warung;
                }
                $data->user = NULL;
                if (!empty($row['user_id'])) {
                    $user = new stdClass();
                    $user->id = (int) $row['user_id'];
                    $user->fullName = $row['user_fullname'];
                    $user->phone = $row['user_phone'];
                    $data->user = $user;
                }
                $data->credit = (int) $row['kredit'] ?? "0";
                $data->debit = (int) $row['debit'] ?? "0";
                $data->type = $row['type'] ?? "";
                $data->status = $row['status'] ?? "";
                $data->bankAccNumber = $row['bank_acc_number'] ?? "";
                $data->bankAccName = $row['bank_acc_name'] ?? "";
                $data->bankUserName = $row['bank_user_name'] ?? "";
                $data->createdAt = $row['created_at'] ?? "";
                $data->updatedAt = $row['updated_at'] ?? "";
                $data->deletedAt = $row['deleted_at'] ?? "";
                array_push($array, $data);
            }
            return resultBody(true, $array);
        } catch (Exception $e) {
            $error = $e->getMessage();
            response(500, $error);
            return resultBody();
        }
}
?>