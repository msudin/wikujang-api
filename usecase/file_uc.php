<?php
include_once('../helper/import.php');

function createFile($userId, $type, $fileName) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $idFile = uniqid();
        $sql = "INSERT INTO `file` (
            `file_id`,
            `type`,
            `file_name`,
            `created_at`
            ) VALUES (
                '$idFile',
                '$type',
                '$fileName',
                '$currentDate'
            )";
        $resultToken = $conn->query($sql);
        $data = new stdClass();
        $data->id = $idFile;
        $data->userId = $userId;
        $data->fileName = $fileName;
        $data->type = $type;
        $data->createdAt = $currentDate;
        return $data;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return NULL;
    }
}

function getAllFile() {
    $conn = callDb();
    $array = array();

    $sql = "SELECT * FROM `file`";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $data = new stdClass();
        $data->id = $row['file_id'];
        $data->type = $row['type'];
        $data->fileName = $row['file_name'];
        $data->fileUrl = urlPathImage()."".$data->fileName;
        $data->createdAt = $row['created_at'];
        array_push($array, $data);
    }
    return $array;
}
?>