<?php 
include_once('../helper/import.php');

function createCategory($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $id = uniqid();

        $isNotExist = checkCategoryExistByName($bodyRequest->categoryName);
        if ($isNotExist) {
            $sql = "INSERT INTO `category` (
                `category_id`,
                `category_name`,
                `created_at`,
                `updated_at`,
                `deleted_at`
                ) VALUES (
                    '$id',
                    '$bodyRequest->categoryName',
                    '$currentDate', 
                    '$currentDate', 
                    ''
                )";
            $conn->query($sql);
            return true;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function deleteCategory($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "UPDATE `category` SET `deleted_at` = '$currentDate' WHERE `category_id` = '$bodyRequest->id'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function updateCategory($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "UPDATE `category` 
            SET `category_name` = '$bodyRequest->categoryName', `updated_at` = '$currentDate' 
            WHERE `category_id` = '$bodyRequest->id'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function getAllCategory() {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT * FROM `category` WHERE `deleted_at` = ''";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) { 
            $data = new stdClass();
            $data->id = $row['category_id'];
            $data->name = $row['category_name'];
            array_push($array, $data);
        }
        return resultBody(true, $array);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody(false);
    }
}
?>