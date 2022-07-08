<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if (requestMethod() == "POST") {
        $entityBody = file_get_contents('php://input');
        $entityData = json_decode($entityBody, true);
        $arrayList = array_values($entityData['district']);
        foreach($arrayList as $entity) {
            $data = new stdClass();
            $data->subDistrictId = (int)$entity['subdistrictId'];
            $data->name = $entity['name'];
            createDistrict($data);
        }
        response("200", "success create district");
    } else if (requestMethod() == "GET") {
        $resultAllDistrict = new stdClass();
        if (isset($_GET['id'])) {
            $resultAllDistrict = getAllDistrict($_GET['id']);
        } else {
            $resultAllDistrict = getAllDistrict();
        }
        response(200, "record found", $resultAllDistrict);
    } else if (requestMethod() == "DELETE") {
        response(200, "Delete Method");
    } else {
        response(500, "Method Not Allowed", $_SERVER["REQUEST_METHOD"]);
    }
} catch (Exception $e) {
    response(500, $e-getMessage());
}

?>