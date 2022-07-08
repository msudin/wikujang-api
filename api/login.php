<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        if ($entityBody != '' && ($entityData["phone"] ?? NULL) != NULL && ($entityData["password"] ?? null) != null) {
            $dUser = getUserByPhone($entityData["phone"]);
            if ($dUser != NULL) {
                if ($dUser->password == $entityData["password"]) {
                    $dToken = getTokenById($dUser->id);
                    if ($dToken != NULL) {
                        $dLogin = new stdClass();
                        $dLogin->userId = $dUser->id;
                        $dLogin->isActive = $dUser->isActive;
                        $dLogin->role = $dUser->role;
                        $dLogin->fullName = $dUser->fullName;
                        $dLogin->accessToken = $dToken->accessToken;
                        $dLogin->expiredAt = $dToken->expiredAt;
                        response(200, "", $dLogin);
                    }
                } else {
                    response(400, "Invalid Nomor Hp / Password");
                }
            }
        } else {
            response(400, "Invalid Nomor Hp / Password");
        }
    } else {
        response(500);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    response(500, "Login exception : $error");
}
?>