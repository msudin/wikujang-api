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
    $userId = NULL
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
            WHERE (b.deleted_at = '' AND w.deleted_at = '' AND u.deleted_at = '')";

            if (!empty($status)) {
                $sql = $sql." AND b.status = '$status'";
            }

            if (!empty($paymentStatus)) {
                if (strtolower($paymentStatus) == "paid" || strtolower($paymentStatus) == "settled") {
                    $sql = $sql." AND i.status = 'PAID' OR i.status = 'SETTLED'";    
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
                    $user->id = $row['user_id'];
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

function getBookingDetail(
    $id = NULL
    ) {
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
                $payment->url = $row['invoice_url'];
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
?>