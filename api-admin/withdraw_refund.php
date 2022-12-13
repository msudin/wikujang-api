<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") { 
        $id = $_GET['id'] ?? NULL;
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dMutasiResponse = getMutasiById($id);
            if ($dMutasiResponse->success) {
                $dMutasi = $dMutasiResponse->data;
                $dUpdateMutasi = updateMutasiStatus($id, "refund");
                if ($dUpdateMutasi->success) {
                    $bodyRequest = new stdClass();
                    $bodyRequest->bookingId = '';
                    $bodyRequest->userId = $dMutasi->userId;
                    $bodyRequest->warungId = $dMutasi->warungId;
                    $bodyRequest->kredit = (int) $dMutasi->debit;
                    $bodyRequest->bankAccNumber = $dMutasi->bankAccNumber;
                    $bodyRequest->bankAccName = $dMutasi->bankAccName;
                    $bodyRequest->userName = $dMutasi->bankUserName;
                    $bodyRequest->debit = 0;
                    $bodyRequest->type = "refund";
                    $bodyRequest->status = "transfered";
                    
                    $dRequest = createMutasi($bodyRequest);
                    if ($dRequest->success) {
                        response(200, "Refund, dana dikembalikan", $bodyRequest);
                    }   
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