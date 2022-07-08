<?php 
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") {
        $headerToken = headerToken();
        if (!isNullOrEmptyString($headerToken)) {
            $dToken = validateToken($headerToken);
            if ($dToken != NULL) {
                $dUser = getUserById($dToken->userId);
                if($dUser != NULL) {
                    response(200, "", $dUser);
                }
            }
        } else {
            response(401);
        }
    } else {
        response(500);
    }
} catch (Exception $e) {
    response(500, $e-getMessage());
}
?>