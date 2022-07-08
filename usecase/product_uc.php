<?php
include_once('../helper/import.php');

function createProduct($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $sql = "INSERT INTO product (
            `product_id`,
            `warung_id`,
            `name`,
            `description`,
            `category`,
            `price`,
            `image_Id`,
            `rating`,
            `discount_percentage`,
            `discount_amount`,
            `likes`,
            `views`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$body->id',
                '$body->warungId',
                '$body->name',
                '$body->description',
                '$body->category',
                $body->price,
                '$body->imageId',
                0,
                0,
                0,
                0,
                0,
                '$currentDate',
                '$currentDate',
                ''
            )";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "Create product exception -> $error");
        return false;
    }
}

function getAllProduct($limit = 0) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.*, p.*
        FROM `file` f
        RIGHT JOIN `product` p ON f.file_id = p.image_id";

        if (!empty($limit)) {
            $sql = $sql." LIMIT $limit";
        }

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->id = $row['product_id'];
            $data->warungId = $row['warung_id'];
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->category = $row['category'];
            $data->price = (int) $row['price'];
            $data->rating = $row['rating'];
            $data->likes = (int) $row['likes'];
            $data->views = (int) $row['rating'];
            $data->rating = $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                if (isset($row["file_name"])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
            }
            $data->createdAt = $row['created_at'];
            $data->updatedAt = $row['updated_at'];
            $data->deletedAt = $row['deleted_at'];
            array_push($array, $data);
        }
        $resultData = new stdClass();
        $resultData->success = true;
        $resultData->data = $array;
        return $resultData;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "Get all product exception -> $error");

        $resultData = new stdClass();
        $resultData->success = false;
        $resultData->data = NULL;
        return $resultData;
    }
}
?>