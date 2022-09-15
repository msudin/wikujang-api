<?php 
include_once('../helper/import.php');

function createInvoiceDb($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "INSERT INTO invoice (
            `invoice_id`,
            `amount`,
            `fees`,
            `invoice_url`,
            `status`,
            `payment_method`,
            `payment_channel`,
            `payment_date`,
            `created_at`,
            `expiry_at`,
            `updated_at`
            ) VALUES (
                '$body->id',
                $body->amount,
                $body->fees,
                '$body->invoiceUrl',
                '$body->status',
                '',
                '',
                '',
                '$currentDate',
                '$body->expiryAt',
                '$currentDate'
            )";
        $conn->query($sql);
        return resultBody(true);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function updateInvoiceDb($bodyRequest) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();
        $invoiceId = $bodyRequest["id"] ?? '';

        $sql = "UPDATE invoice SET `updated_at` = '$updatedAt'";
        
        if (!empty($bodyRequest["status"])) {
            $status = $bodyRequest["status"];
            $sql = $sql.", `status` = '$status'";
        }

        if (!empty($bodyRequest['payment_method'])) { 
            $payment_method = $bodyRequest['payment_method'];
            $sql = $sql.", `payment_method` = '$payment_method'";
        }

        if (!empty($bodyRequest['payment_channel'])) { 
            $payment_channel = $bodyRequest['payment_channel'];
            $sql = $sql.", `payment_channel` = '$payment_channel'";
        }

        if (!empty($bodyRequest['paid_at'])) { 
            $paid_at = $bodyRequest['paid_at'];
            $sql = $sql.", `payment_date` = '$paid_at'";
        }

        $sql = $sql." WHERE `invoice_id`= '$invoiceId'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}
?>