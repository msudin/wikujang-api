<?php
include_once('../helper/import.php');

function createSubdistrict($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $sql = "INSERT INTO subdistrict (
            `subdistrict_id`,
            `subdistrict_name`,
            `postal_code`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                $bodyRequest->id,
                '$bodyRequest->name',
                '$bodyRequest->postalCode',
                '$currentDate',
                '$currentDate',
                ''
            )";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "create subdistrict exception -> $error");
        return false;
    }
}

function getAllSubdistrict() {
    $conn = callDb();
    $array = array();

    $sql = "SELECT * FROM `subdistrict`";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $data = new stdClass();
        $data->id = (Int) $row['subdistrict_id'];
        $data->name = $row['subdistrict_name'];
        $data->postalCode = (Int) $row['postal_code'];
        $data->createdAt = $row['created_at'];
        array_push($array, $data);
    }
    return $array;
}

?>