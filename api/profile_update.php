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
                $isSuccess = updateProfile($entityData, $dToken->userId);
                if ($isSuccess) {
                    response(200, "Berhasil update profil");
                }
            }
        } else {
            response(500);
        }
    } else {
       response(500);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    response(500, $error);
}

?>