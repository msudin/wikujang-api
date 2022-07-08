<?php
include_once('../helper/import.php');

function createDistrict($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $sql = "INSERT INTO district (
            `subdistrict_id`,
            `district_name`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                 $bodyRequest->subdistrictId,
                 '$bodyRequest->name',
                 '$currentDate',
                 '$currentDate',
                 ''
            )";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "create district exception -> $error");
        return false;
    }
}

function getAllDistrict($id = null) {
    $conn = callDb();
    $array = array();

    $sql = "";
    if(isset($id)) {
        $sql = "SELECT * FROM `district` WHERE `subdistrict_id`=$id";
    } else {
        $sql = "SELECT * FROM `district`";
    }
    
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $data = new stdClass();
        $data->id = (Int) $row['district_id'];
        $data->subddDistrictId = (Int) $row['subdistrict_id'];
        $data->name = $row['district_name'];
        $data->createdAt = $row['created_at'];
        array_push($array, $data);
    }
    return $array;   
}

?>