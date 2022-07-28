<?php 
include_once('../helper/import.php');

try {
    if (requestMethod() == "POST") { 
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $bodyRequest = new stdClass();
            $bodyRequest->id = $data['categoryId'] ?? NULL;
            $bodyRequest->categoryName = $data['categoryName'] ?? NULL;
            $isSuccess = updateCategory($bodyRequest);
            if ($isSuccess) {
                response(200, "Berhasil update kategori menu");
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