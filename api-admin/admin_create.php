<?php 
include_once('../helper/import.php');

try {
    if (requestMethod() == "POST") { 
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody, true);
        if ($entityBody != '') {
            $bodyRequest = new stdClass();
            $bodyRequest->fullName = $data['fullName'] ?? NULL;
            $bodyRequest->userName = $data['userName'] ?? NULL;
            $bodyRequest->password = $data['password'] ?? NULL;
            $bodyRequest->email = $data['email'] ?? NULL;
            if (!(registerCheckAdminExist($bodyRequest->email, $bodyRequest->userName))) {
                $bodyRequest->password = base64_encode($bodyRequest->password);
                $isSuccessCreateAdmin = createAdmin($bodyRequest);
                if ($isSuccessCreateAdmin) {
                    response(200, "Success create admin", $bodyRequest);
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