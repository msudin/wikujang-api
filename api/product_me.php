<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "GET") {
        $headerToken = headerToken();
        if (!empty($headerToken)) {
            $dToken = validateToken($headerToken);
            if ($dToken != NULL) { 
                $result = getProductMe($dToken->warungId);
                if ($result->success == true) {
                    response(200, "record found", $result->data);
                }
            }
        } else {
            response(401);
        }
    } else {
        response(500, "Method not allowed");
    }
} catch (Exception $e) {
    response(500, $e->getMessage());
}
?>