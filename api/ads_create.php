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
                if ($dToken->userId == 0 || !empty($dToken->warungId)) {
                    $bodyRequest = new stdClass();
                    $bodyRequest->warungId = $dToken->warungId;
                    $bodyRequest->name = $data['name'] ?? "";
                    $bodyRequest->description = $data['description'] ?? "";
                    $bodyRequest->imageId = $data['imageId'] ?? "";
                    $bodyRequest->status = $data['status'] ?? "waiting_payment";
                    $bodyRequest->startDate = $data['startDate'] ?? '';
                    $bodyRequest->endDate = $data['endDate'] ?? '';
                    
                    $dAds = createAds($bodyRequest);
                    if ($dAds->success) {
                        response(200, "Berhasil tambah iklan");
                    }
                } else {
                    response(400, "Silahkan buka warung terlebih dahulu");
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