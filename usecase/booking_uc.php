<?php 
include_once('../helper/import.php');

function createBooking($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "INSERT INTO booking (
            `booking_id`,
            `user_id`,
            `warung_id`,
            `invoice_id`,
            `dp_amount`,
            `status`,
            `date`,
            `time`,
            `reason`,
            `person`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$body->id',
                '$body->userId',
                '$body->warungId',
                '$body->invoiceId',
                $body->dpAmount,
                '$body->status',
                '$body->date',
                '$body->time',
                '$body->reason',
                $body->person,
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

function getBookingAll(
    $status = NULL, 
    $limit = NULL, 
    $paymentStatus = NULL, 
    $warungId = NULL,
    $userId = NULL,
    $bookingDate = NULL
    ) {
        try {
            $conn = callDb();
            $array = array();

            $sql = "SELECT 
            w.name as warung_name,
            u.fullname as user_fullname,
            u.phone as user_phone,
            i.status as payment_status,
            i.expiry_at as payment_expired,
            i.payment_date,
            i.payment_method,
            i.payment_channel,
            i.invoice_url,
            i.amount,
            i.fees,
            i.booking,
            b.*
            FROM `booking` b
            LEFT JOIN `warung` w ON b.warung_id = w.warung_id
            LEFT JOIN `invoice` i ON b.invoice_id = i.invoice_id
            LEFT JOIN `user` u ON b.user_id = u.user_id
            WHERE b.deleted_at = ''";

            if (!empty($status)) {
                $sql = $sql." AND b.status = '$status'";
            }

            if (!empty($bookingDate)) {
                $sql = $sql." AND b.date = '$bookingDate'";
            }

            if (!empty($paymentStatus)) {
                if (strtolower($paymentStatus) == "paid" || strtolower($paymentStatus) == "settled") {
                    $sql = $sql." AND (i.status = 'PAID' OR i.status = 'SETTLED')";    
                } else {
                    $statusPay = strtoupper($paymentStatus);
                    $sql = $sql." AND i.status = '$statusPay'";
                }
            }

            if (!empty($warungId)) {
                $sql = $sql." AND b.warung_id = '$warungId'";
            }

            if (!empty($userId)) {
                $sql = $sql." AND b.user_id = '$userId'";
            }

            $sql = $sql." ORDER BY b.created_at DESC";
            if (!empty($limit)) {
                $sql = $sql." LIMIT $limit";
            }
            
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['booking_id'];
                $data->status = $row['status'];
                $data->dpAmount = (int) $row['dp_amount'];
                $data->date = $row['date'];
                $data->time = $row['time'];
                $data->reason = $row['reason'];
                $data->person = (int) $row['person'];
                                
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

                /// payment invoice
                $payment = new stdClass();
                $payment->id = $row['invoice_id'];
                $payment->amount = (int) $row['amount'];
                $payment->fees = (int) $row['fees'];
                $payment->booking = (int) $row['booking'];
                $payment->status = $row['payment_status'] ?? "";
                $payment->url = $row['invoice_url'] ?? "";
                $payment->method = $row['payment_method'] ?? "";
                $payment->channel = $row['payment_channel'] ?? "";
                $payment->paymentDate = $row['payment_date'] ?? "";
                $payment->paymentExpired = $row['payment_expired'] ?? "";
                $data->invoice = $payment;

                $data->createdAt = $row['created_at'];
                $data->updatedAt = $row['updated_at'];
                $data->deletedAt = $row['deleted_at'];
                array_push($array, $data);
            }
            return resultBody(true, $array);
        } catch (Exception $e) {
            $error = $e->getMessage();
            response(500, $error);
            return resultBody();
        }
}

function getBookingDetail($id = NULL) {
        try {
            $conn = callDb();

            $sql = "SELECT 
            i.status as payment_status,
            i.expiry_at as payment_expired,
            i.payment_date,
            i.payment_method,
            i.payment_channel,
            i.invoice_url,
            i.amount,
            i.booking,
            i.fees,
            b.*
            FROM `booking` b
            LEFT JOIN `invoice` i ON b.invoice_id = i.invoice_id
            WHERE booking_id = '$id'";
            
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['booking_id'];
                $data->status = $row['status'];
                $data->dpAmount = (int) $row['dp_amount'];
                $data->date = $row['date'];
                $data->time = $row['time'];
                $data->reason = $row['reason'];
                $data->person = (int) $row['person'];

                $data->warung = NULL;
                if (!empty($row['warung_id'])) {
                    $warung = new stdClass();
                    $dWarung = getWarungById($row['warung_id'], false);
                    $data->warung = $dWarung->data;
                }

                $data->user = NULL;
                if (!empty($row['user_id'])) {
                    $warung = new stdClass();
                    $dUser = getUserById($row['user_id']);
                    $data->user = $dUser;
                }

                /// payment invoice
                $payment = new stdClass();
                $payment->id = $row['invoice_id'];
                $payment->amount = (int) $row['amount'];
                $payment->fees = (int) $row['fees'];
                $payment->booking = (int) $row['booking'];
                $payment->status = $row['payment_status'] ?? "";
                $payment->url = $row['invoice_url'] ?? "";
                $payment->method = $row['payment_method'] ?? "";
                $payment->channel = $row['payment_channel'] ?? "";
                $payment->paymentDate = $row['payment_date'] ?? "";
                $payment->paymentExpired = $row['payment_expired'] ?? "";
                $data->invoice = $payment;

                $data->createdAt = $row['created_at'];
                $data->updatedAt = $row['updated_at'];
                $data->deletedAt = $row['deleted_at'];
                return resultBody(true, $data);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            response(500, $error);
            return resultBody();
        }
}

function updateBooking($bodyRequest) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();
        $bookingId = $bodyRequest['id'] ?? '';

        $sql = "UPDATE booking SET `updated_at` = '$updatedAt'";
        
        if (!empty($bodyRequest['userId'])) {
            $userId = $bodyRequest['userId'];
            $sql = $sql.", `user_id` = '$userId'";
        }

        if (!empty($bodyRequest['warungId'])) { 
            $warungId = $bodyRequest['warungId'];
            $sql = $sql.", `warung_id` = '$warungId'";
        }

        if (!empty($bodyRequest['invoiceId'])) { 
            $invoiceId = $bodyRequest['invoiceId'];
            $sql = $sql.", `invoice_id` = '$invoiceId'";
        }

        if (!empty($bodyRequest['dpAmount'])) { 
            $dpAmount = $bodyRequest['dpAmount'];
            $sql = $sql.", `dp_amount` = '$dpAmount'";
        }

        if (!empty($bodyRequest['status'])) {
            $status = $bodyRequest['status'];
            $sql = $sql.", `status` = '$status'";
        }

        if (!empty($bodyRequest['date'])) {
            $date = $bodyRequest['date'];
            $sql = $sql.", `date` = '$date'";
        }

        if (!empty($bodyRequest['time'])) {
            $time = $bodyRequest['time'];
            $sql = $sql.", `time` = '$time'";
        }

        if (!empty($bodyRequest['reason'])) {
            $reason = $bodyRequest['reason'];
            $sql = $sql.", `reason` = '$reason'";
        }

        if (!empty($bodyRequest['person'])) {
            $person = $bodyRequest['person'];
            $sql = $sql.", `person` = '$person'";
        }

        /// QUERY DELETE BOOKING
        if (!empty($bodyRequest['deleted']) && $bodyRequest['deleted'] == 'true') {
            $sql = $sql.", `deleted_at` = '$updatedAt'";
        }

        /// QUERY RE-ACTIVATE BOOKING
        if (!empty($bodyRequest['activated']) && $bodyRequest['activated'] == 'true' ) {
            $sql = $sql.", `deleted_at` = ''";
        }

        $sql = $sql." WHERE `booking_id`= '$bookingId'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function bookingRevenue($paymentDate = NULL, $paymentStatus = NULL) {
    try {
        $conn = callDb();
        $sql = "SELECT SUM(amount) as total_amount, COUNT(amount) as total_data FROM `invoice`
        WHERE NOT `booking` = 0"; 

        if (!empty($paymentDate)) {
            $sql = $sql." AND `payment_date` LIKE '%$paymentDate%'"; 
        }

        if (!empty($paymentStatus)) {
            if (strtolower($paymentStatus) == "paid" || strtolower($paymentStatus) == "settled") {
                $sql = $sql." AND (`status` = 'PAID' OR `status` = 'SETTLED')";    
            } else {
                $statusPay = strtoupper($paymentStatus);
                $sql = $sql." AND `status` = '$statusPay'";
            }
        }

        $sql = $sql." ORDER BY created_at DESC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->totalAmount =  (int) $row['total_amount'] ?? "0";
            $data->totalData = (int) $row['total_data'] ?? "0";
            return resultBody(true, $data);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function summaryBooking($date = NULL) {
    try {
        clearstatcache();
        $array = array();

        $conn = callDb();
        $sql = "SELECT w.*, 
        COUNT(b.warung_id) total_booking,
        COUNT(case b.status when 'waiting_approval' then 1 else null end) total_waiting,
        COUNT(case b.status when 'approved' then 1 else null end) total_approved, 
        COUNT(case i.status when 'PAID' then 1 else null end) total_paid, 
        COUNT(case i.status when 'SETTLED' then 1 else null end) total_settled,
        COUNT(case i.status when 'EXPIRED' then 1 else null end) total_expired,
        COUNT(case b.status when 'rejected' then 1 else null end) total_rejected 
        FROM `warung` w 
        LEFT JOIN `booking` b ON w.warung_id = b.warung_id 
        LEFT JOIN `invoice` i ON b.invoice_id = i.invoice_id"; 

        if (!empty($date)) {
            $sql = $sql." WHERE b.date LIKE '%$date%'"; 
        }

        $sql = $sql." GROUP BY w.warung_id ORDER BY total_booking DESC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->warungId =  $row['warung_id'] ?? "0";
            $data->warungName =  $row['name'] ?? "0";
            $data->totalBooking =  (int) $row['total_booking'] ?? "0";
            $data->totalWaiting =  (int) $row['total_waiting'] ?? "0";
            $data->totalApproved =  (int) $row['total_approved'] ?? "0";
            $settled = (int) $row['total_settled'] ?? "0";
            $paid = (int) $row['total_paid'] ?? "0";
            $data->totalPaid = $settled + $paid;
            $data->totalExpired = (int) $row['total_expired'] ?? "0";
            $data->totalRejected = (int) $row['total_rejected'] ?? "0";
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