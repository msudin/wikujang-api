<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        $headerToken = headerToken();
        if ($entityBody != '') {
            if (!empty($headerToken)) {
                $dToken = validateToken($headerToken);
                if ($dToken != NULL) {
                    $bodyRequest = new stdClass();
                    $bodyRequest->id = uniqid();
                    $bodyRequest->userId = $dToken->userId;
                    $bodyRequest->name = $data['name'] ?? "";
                    $bodyRequest->userName = $data['userName'] ?? "";
                    $bodyRequest->description = $data['description'] ?? "";
                    $bodyRequest->isOpen = $data['isOpen'] ?? 'true';
                    $bodyRequest->openTime = $data['openTime'] ?? '07.00';
                    $bodyRequest->closedTime = $data['closedTime'] ?? '22.00';
                    $bodyRequest->rating = $data['rating'] ?? 0;
                    $bodyRequest->imageId = $data['imageId'] ?? "";

                    $bodyRequest->subDistrictId = $data['subDistrictId'] ?? 0;
                    $bodyRequest->districtId = $data['districtId'] ?? 0;
                    $bodyRequest->address = $data['address'] ?? "";
                    $bodyRequest->latitude = $data['latitude'] ?? "";
                    $bodyRequest->longitude = $data['longitude'] ?? "";

                    if (!(validateWarungExist($dToken->userId, $bodyRequest->userName))) {
                       $isSuccessCreateWarung = createWarung($bodyRequest);
                       if($isSuccessCreateWarung) {
                           $body = new stdClass();
                           $body->role = "warung";
                           $body->userId = $bodyRequest->userId;
                           $isSuccessUpdateUserRole = updateUserRole($body);
                           if ($isSuccessUpdateUserRole) {
                               response(200, "Berhasil Buka Warung", $bodyRequest);
                           }
                       }
                    }
                }
            } else {
                response(401);
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