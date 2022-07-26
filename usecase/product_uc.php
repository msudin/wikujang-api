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
        response(500, "$error");
        return false;
    }
}

function getAllProduct($limit = 0) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.*, w.deleted_at, w.warung_id, p.* 
        FROM `product` p 
        LEFT JOIN `file` f ON p.image_id = f.file_id 
        LEFT JOIN `warung` w ON p.warung_id = w.warung_id 
        WHERE w.deleted_at='' AND p.deleted_at = ''";

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
            $data->views = (int) $row['views'];
            $data->rating = $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                if (isset($row["file_name"])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
            }
            array_push($array, $data);
        }
        $resultData = new stdClass();
        $resultData->success = true;
        $resultData->data = $array;
        return $resultData;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");

        $resultData = new stdClass();
        $resultData->success = false;
        $resultData->data = NULL;
        return $resultData;
    }
}

function getProductMe($warungId) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.*, p.*
        FROM `file` f
        RIGHT JOIN `product` p ON f.file_id = p.image_id
        WHERE warung_id = '$warungId'";

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
            $data->views = (int) $row['views'];
            $data->rating = $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                if (isset($row["file_name"])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
            }
            array_push($array, $data);
        }
        $resultData = new stdClass();
        $resultData->success = true;
        $resultData->data = $array;
        return $resultData;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");

        $resultData = new stdClass();
        $resultData->success = false;
        $resultData->data = NULL;
        return $resultData;
    }
}

function updateViews($productId) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();

        $sqlSelectViews = "SELECT `views` FROM product WHERE `product_id` = '$productId'";
        $result = $conn->query($sqlSelectViews);
        while($row = $result->fetch_assoc()) { 
            $currentViews = (int) $row['views'];
            $newViews = $currentViews + 1;
            $sql = "UPDATE `product` SET 
                `updated_at` = '$updatedAt',
                `views` = $newViews 
            WHERE `product_id`= '$productId'";
            $conn->query($sql);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
    }
}

function getProductById($productId) {
    updateViews($productId);
    try {
        $conn = callDb();
        $data = new stdClass();

        $sql = "SELECT f.*, p.*
        FROM `file` f
        RIGHT JOIN `product` p ON f.file_id = p.image_id
        WHERE product_id = '$productId'";

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data->id = $row['product_id'];
            $data->warungId = $row['warung_id'];
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->category = $row['category'];
            $data->price = (int) $row['price'];
            $data->rating = $row['rating'];
            $data->likes = (int) $row['likes'];
            $data->views = (int) $row['views'];
            $data->rating = $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                if (isset($row["file_name"])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
            }

            $dWarung = getWarungById($data->warungId);
            if ($dWarung->success) {
                $data->warung = $dWarung->data;
            }
        }
        $resultData = new stdClass();
        $resultData->success = true;
        $resultData->data = $data;
        return $resultData;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);

        $resultData = new stdClass();
        $resultData->success = false;
        $resultData->data = NULL;
        return $resultData;
    }
}
?>