<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $headerToken = headerToken();
            $dToken = validateToken($headerToken);
            if (!empty($dToken)) {
                $resultWarung = getWarungById($dToken->userId);
                if ($resultWarung->success == true) {
                    
                    // $bodyRequest = new stdClass();
                    // $bodyRequest->warungId = $resultWarung->data->id ;
                    // $bodyRequest->name = $data['name'] ?? "";
                    // $bodyRequest->userName = $data['userName'] ?? "";
                    // $bodyRequest->description = $data['description'] ?? "";
                    // $bodyRequest->isOpen = $data['isOpen'] ?? 'true';
                    // $bodyRequest->openTime = $data['openTime'] ?? '07.00';
                    // $bodyRequest->closedTime = $data['closedTime'] ?? '22.00';
                    // $bodyRequest->rating = $data['rating'] ?? 0;
                    // $bodyRequest->imageId = $data['imageId'] ?? "";

                    // $bodyRequest->subDistrictId = $data['subDistrictId'] ?? 0;
                    // $bodyRequest->districtId = $data['districtId'] ?? 0;
                    // $bodyRequest->address = $data['address'] ?? "";
                    // $bodyRequest->latitude = $data['latitude'] ?? "";
                    // $bodyRequest->longitude = $data['longitude'] ?? "";
                    
                    $isSuccess = updateWarung($entityData, $resultWarung->data->id);
                    if ($isSuccess) {
                        response(200, "Berhasil update profil");
                    }
                } else {
                    response(400);
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