<?php 
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") {
        $dToken = headerAccessToken();
        if ($dToken != NULL) {
            $dUser = getUserById($dToken->userId);
            if ($dUser != NULL) {
                response(200, "", $dUser);
            }
        }
    } else {
        response(500);
    }
} catch (Exception $e) {
    response(500, $e-getMessage());
}
?>