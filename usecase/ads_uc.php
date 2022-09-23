<?php 
include_once('../helper/import.php');

function createAds($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "INSERT INTO ads (
            `ads_id`,
            `warung_id`,
            `name`,
            `description`,
            `image_id`,
            `status`,
            `start_date`,
            `end_date`,
            `invoice_id`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$body->id',
                '$body->warungId',
                '$body->name',
                '$body->description',
                '$body->imageId',
                '$body->status',
                '$body->startDate',
                '$body->endDate',
                '$body->invoiceId',
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

function getAdsAll(
    $status = NULL, 
    $limit = NULL, 
    $paymentStatus = NULL, 
    $warungId = NULL
    ) {
        try {
            $conn = callDb();
            $array = array();

            $sql = "SELECT 
            f.file_name,
            w.name as warung_name,
            i.status as payment_status,
            i.expiry_at as payment_expired,
            i.payment_date,
            i.payment_method,
            i.payment_channel,
            i.invoice_url,
            i.amount,
            i.fees,
            a.*
            FROM `ads` a
            LEFT JOIN `file` f ON a.image_id = f.file_id 
            LEFT JOIN `warung` w ON a.warung_id = w.warung_id
            LEFT JOIN `invoice` i ON a.invoice_id = i.invoice_id
            WHERE (a.deleted_at = '' AND w.deleted_at = '')";

            if (!empty($status)) {
                $sql = $sql." AND a.status = '$status'";
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
                $sql = $sql." AND a.warung_id = '$warungId'";
            }

            $sql = $sql." ORDER BY a.created_at DESC";
            if (!empty($limit)) {
                $sql = $sql." LIMIT $limit";
            }
            
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['ads_id'];
                $data->name = $row['name'];
                $data->description = $row['description'];
                $data->status = $row['status'];
                $data->startDate = $row['start_date'];
                $data->endDate = $row['end_date'];
                $data->imageId = $row['image_id'];
                $data->imageUrl = "";
                if (!empty($row['file_name'])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
                $data->warung = NULL;
                if (!empty($row['warung_id'])) {
                    $warung = new stdClass();
                    $warung->id = $row['warung_id'];
                    $warung->name = $row['warung_name'];
                    $data->warung = $warung;
                }

                /// payment invoice
                $payment = new stdClass();
                $payment->id = $row['invoice_id'];
                $payment->amount = (int) $row['amount'];
                $payment->fees = (int) $row['fees'];
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

function getAdsDetail(
    $id = NULL
    ) {
        try {
            $conn = callDb();

            $sql = "SELECT 
            f.file_name,
            w.name as warung_name,
            i.status as payment_status,
            i.expiry_at as payment_expired,
            i.payment_date,
            i.payment_method,
            i.payment_channel,
            i.invoice_url,
            i.amount,
            i.fees,
            a.*
            FROM `ads` a
            LEFT JOIN `file` f ON a.image_id = f.file_id 
            LEFT JOIN `warung` w ON a.warung_id = w.warung_id
            LEFT JOIN `invoice` i ON a.invoice_id = i.invoice_id
            WHERE ads_id = '$id'";
            
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['ads_id'];
                $data->name = $row['name'];
                $data->description = $row['description'];
                $data->status = $row['status'];
                $data->startDate = $row['start_date'];
                $data->endDate = $row['end_date'];
                $data->imageId = $row['image_id'];
                $data->imageUrl = "";
                if (!empty($row['file_name'])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
                $data->warung = NULL;
                if (!empty($row['warung_id'])) {
                    $warung = new stdClass();
                    $warung->id = $row['warung_id'];
                    $warung->name = $row['warung_name'];
                    $data->warung = $warung;
                }

                /// payment invoice
                $payment = new stdClass();
                $payment->id = $row['invoice_id'];
                $payment->amount = (int) $row['amount'];
                $payment->fees = (int) $row['fees'];
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

function updateAds($bodyRequest) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();
        $adsId = $bodyRequest['id'] ?? '';

        $sql = "UPDATE ads SET `updated_at` = '$updatedAt'";
        
        if (!empty($bodyRequest['name'])) {
            $name = $bodyRequest['name'];
            $sql = $sql.", `name` = '$name'";
        }

        if (!empty($bodyRequest['description'])) { 
            $description = $bodyRequest['description'];
            $sql = $sql.", `description` = '$description'";
        }

        if (!empty($bodyRequest['startDate'])) { 
            $startDate = $bodyRequest['startDate'];
            $sql = $sql.", `start_date` = '$startDate'";
        }

        if (!empty($bodyRequest['endDate'])) { 
            $endDate = $bodyRequest['endDate'];
            $sql = $sql.", `end_date` = '$endDate'";
        }

        if (!empty($bodyRequest['status'])) {
            $status = $bodyRequest['status'];
            $sql = $sql.", `status` = '$status'";
        }

        if (!empty($bodyRequest['imageId'])) {
            $imageId = $bodyRequest['imageId'];
            $sql = $sql.", `image_id` = '$imageId'";
        }

        /// QUERY DELETE ADS
        if (!empty($bodyRequest['deleted']) && $bodyRequest['deleted'] == 'true') {
            $sql = $sql.", `deleted_at` = '$updatedAt'";
        }

        /// QUERY RE-ACTIVATE ADS
        if (!empty($bodyRequest['activated']) && $bodyRequest['activated'] == 'true' ) {
            $sql = $sql.", `deleted_at` = ''";
        }

        $sql = $sql." WHERE `ads_id`= '$adsId'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function adsRevenue($paymentDate = NULL, $paymentStatus = NULL) {
    try {
        $conn = callDb();
        $sql = "SELECT SUM(amount) as total_amount, COUNT(amount) as total_data FROM `invoice` 
        WHERE `payment_date` LIKE '%$paymentDate%'"; 

        if (!empty($paymentStatus)) {
            if (strtolower($paymentStatus) == "paid" || strtolower($paymentStatus) == "settled") {
                $sql = $sql." AND `status` = 'PAID' OR `status` = 'SETTLED'";    
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
?>