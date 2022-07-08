<?php
include_once('../helper/import.php');

try {
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        $arrayList = array_values($entityData['subdistrict']);
        foreach($arrayList as $entity) {
            $data = new stdClass();
            $data->id = (int)$entity["id"];
            $data->name = $entity['name'];
            $data->postalCode = $entity['postalCode'];
            createSubdistrict($data);
        }
        response("200", "Success create subdistrict");
    } else if (requestMethod() == "GET"){
        $resultSubdistrict = getAllSubdistrict();
        response(200, "record found", $resultSubdistrict);
    } else {
        response(500, "Method Not Allowed");
    }
} catch (Exception $e) {
    response(500, $e-getMessage());
}

?>