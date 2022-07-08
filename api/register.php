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
            $bodyRequest->phone = $data['phone'] ?? NULL;
            $bodyRequest->password = $data['password'] ?? NULL;
            $bodyRequest->email = $data['email'] ?? NULL;
            $bodyRequest->birthdate = $data['birthdate'] ?? "2022-02-27";
            $bodyRequest->gender = $data["gender"] ?? "Laki-laki";
            if (!(registerCheckUserExist($bodyRequest->phone, $bodyRequest->password))) {
               $isSuccessCreateUser = createUserRegister($bodyRequest);
               if($isSuccessCreateUser) {
                   $userData = getUserByPhone($bodyRequest->phone);
                   if ($userData != NULL) {
                       $dToken = createToken($userData->id);
                       if (!empty($dToken)) {
                           $dToken->userId = $userData->id;
                           $dToken->fullName = $userData->fullName;
                           $dToken->username = $userData->userName;
                           $dToken->isActive = $userData->isActive;
                           $dToken->role = $userData->role;
                           response(200, "Register berhasil", $dToken);
                       }
                   }
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