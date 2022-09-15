<?php
include_once('../helper/import.php');
try {
    clearstatcache();
    if (headerTokenXendit() == "ZetzaBntVcTTwB7KXyR75DJXd8zXMlD453noDHKPAwsesVRf") {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $dInvoice = updateInvoiceDb($data);
            if ($dInvoice == true) {
                response(200, "Berhasil update invoice");
            }
        } else {
            response(400);
        }
    } else {
        response(400, "Token tidak valid");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>