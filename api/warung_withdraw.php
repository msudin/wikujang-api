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
                $bodyRequest->bookingId = '';
                $bodyRequest->userId = $dToken->userId;
                $bodyRequest->warungId = $dToken->warungId;
                $bodyRequest->kredit = 0;
                $bodyRequest->bankAccNumber = $data['bankAccNumber'];
                $bodyRequest->bankAccName = $data['bankAccName'];
                $bodyRequest->userName = $data['bankUserName'];
                $bodyRequest->debit = (int) $data['amount'];
                $bodyRequest->type = "withdraw";
                $bodyRequest->status = "";
                
                $dRequest = createMutasiBooking($bodyRequest);
                if ($dRequest->success) {
                    response(200, "Withdraw sedang di proses, silahkan tunggu 3 x 24 jam");
                }   
            }
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>