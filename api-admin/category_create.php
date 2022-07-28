<?php 
include_once('../helper/import.php');

try {
    if (requestMethod() == "POST") { 
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if (!empty($entityBody)) {
            $bodyRequest = new stdClass();
            $bodyRequest->categoryName = $data['categoryName'] ?? NULL;
            if (empty($bodyRequest->categoryName)) {
                response(400, "Nama category mandatory");    
            } else {
                $isSuccess = createCategory($bodyRequest);
                if ($isSuccess) {
                    response(200, "Berhasil tambah kategori menu");
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