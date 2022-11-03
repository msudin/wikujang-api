<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (headerTokenXendit() == callbackTokenXendit()) {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $dInvoice = updateInvoiceDb($data);
            if ($dInvoice == true) {
                if (strtolower($data['status']) == 'paid' &&  strtolower(((reset($data['fees']))['type'])) == 'admin booking') {
                    $dRequest = webhookBookingHandler($data);
                    if ($dRequest->success) {
                        response(200, "success update data & created mutasi history");
                    }
                } else {
                    response(200, "success update data");
                }
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