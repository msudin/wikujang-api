<?php
include_once('../helper/import.php');

try {
    if (requestMethod() == "GET") {
        /// GET ALL FILE
        $resultFile = getAllFile();
        response(200, "record found", $resultFile);
    }
} catch (Exception $ex) {
    response(500, $e->getMessage());
}
?>