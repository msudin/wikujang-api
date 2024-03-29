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
                $bodyRequest->warungId = $dToken->warungId ?? "";
                $bodyRequest->name = $data['name'] ?? "";
                $bodyRequest->description = $data['description'] ?? "";
                $bodyRequest->categoryId = $data['categoryId'] ?? "";
                $bodyRequest->price = (int) $data['price'] ?? 0;
                $bodyRequest->discountAmount = (int) $data['discountAmount'] ?? 0;
                $bodyRequest->imageId = $data['imageId'] ?? "";

                if (!empty($bodyRequest->warungId)) {
                    $isSuccess = createProduct($bodyRequest);
                    if ($isSuccess) {
                        response(200, "Berhasil menambahkan produk", $bodyRequest);
                    }
                } else {
                    response(400, "Id warung tidak boleh kosong");
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