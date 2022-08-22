<?php
include_once('../helper/import.php');

try {
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        if ($entityBody != '') {
            $data = json_decode($entityBody, true);

            // Bulk Data Rating
            if (!empty($data["productRating"])) {
                bulkRatingMenu();
            }
        }
        response(200, "success bulk data");
    } else {
        response(500, "Method not allowed");
    }
} catch(Exception $e) {
    response(500, $e->getMessage());
}

?>