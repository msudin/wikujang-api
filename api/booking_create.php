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
                $bodyRequest = new stdClass();
                $bodyRequest->id = uniqid();
                $bodyRequest->userId = $dToken->userId;
                $bodyRequest->warungId = $data['warungId'];
                $bodyRequest->dpAmount = (int) $data['dpAmount'] ?? "0";
                $bodyRequest->status = "waiting_approval";
                $bodyRequest->invoiceId = "";
                $bodyRequest->date = $data['date'] ?? "";
                $bodyRequest->time = $data['time'] ?? "";
                $bodyRequest->reason = $data['reason'] ?? "";
                $bodyRequest->person = (int) $data['person'] ?? "0";

                $dCreateBooking = createBooking($bodyRequest);
                if ($dCreateBooking->success) {
                    response(200, "Berhasil pengajuan booking tempat");
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