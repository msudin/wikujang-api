<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        $headerToken = headerToken();
        if (!empty($entityBody)) {
            $dToken = validateToken($headerToken);
            if ($dToken != NULL) {
                $bodyRequest = new stdClass();
                $bodyRequest->productId = $data['productId'] ?? "";
                $bodyRequest->userId = $dToken->userId;
                $bodyRequest->rating = $data['rating'] ?? 0;
                $bodyRequest->comment = $data['comment'] ?? "";
                $bodyRequest->imageId = $data['imageId'] ?? "";
                $dReview = createComment($bodyRequest);
                if ($dReview->success) {
                    response(200, "Terima kasih ulasannya");
                    
                    // Update Rating Menu [Manually]
                    // bulkRatingByMenuId($bodyRequest->productId);
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