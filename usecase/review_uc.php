<?php 
include_once('../helper/import.php');

function createComment($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $id = uniqid();

        $sql = "INSERT INTO `review` (
            `review_id`,
            `product_id`,
            `user_id`,
            `comment`,
            `image_id`,
            `rating`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$id',
                '$body->productId',
                '$body->userId',
                '$body->comment',
                '$body->imageId',
                $body->rating,
                '$currentDate',
                '$currentDate',
                ''
            )";
        $result = $conn->query($sql);
        return resultBody(true);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getAverageRatingProduct($dProductId) {
    try {
        $conn = callDb();
        $rating = 0;

        $sql = "SELECT AVG(`rating`) AS avg_rating FROM `review` WHERE product_id = '$dProductId' AND deleted_at = ''";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $temp = $row['avg_rating'];
            $rating = number_format((double)$temp, 2, '.', '');
        }
        return resultBody(true, (double) $rating);
    } catch (Exception $e) {
        return resultBody();
    }
}

function getAverageRatingWarung($warungId) {
    try {
        $conn = callDb();
        $rating = 0;

        $sql = "SELECT AVG(r.rating) AS avg_rating, r.deleted_at FROM `product` p 
        LEFT JOIN `review` r ON p.product_id = r.product_id
        WHERE `warung_id` = '$warungId' AND r.deleted_at = ''";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $temp = $row['avg_rating'];
            $rating = number_format((double)$temp, 2, '.', '');
        }
        return resultBody(true, (double) $rating);
    } catch (Exception $e) {
        return resultBody();
    }
}

function getAllReviewByProductId($dProductId) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.file_name, f.type, r.* 
        FROM `review` r 
        LEFT JOIN `file` f ON r.image_id = f.file_id";
        if (!empty($dProductId)) {
            $sql = $sql." WHERE product_id = '$dProductId'";
        }
        $sql = $sql." ORDER BY created_at DESC";

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->id = $row['review_id'];
            $data->productId = $row['product_id'];
            $data->comment = $row['comment'];
            $data->rating = (double) $row['rating'];
            $data->image = null;
            $data->user = null;
            if (!empty($row["image_id"])) {
                $arrayImage = array();
                $photo = new stdClass();
                $photo->id = $row["image_id"];
                $photo->fileUrl = urlPathImage()."".$row['file_name'];
                $photo->fileName = $row['file_name'];
                array_push($arrayImage, $photo);
                $data->image = $arrayImage;
            }
            if (!empty($row['user_id'])) {
                $dUser = getUserById($row['user_id'], false);
                if ($dUser != NULL) {
                    $data->user = $dUser;
                }
            }
            $data->createdAt = $row["created_at"];
            $data->updatedAt = $row["updated_at"];
            $data->deletedAt = $row["deleted_at"];
            array_push($array, $data);
        }
        return resultBody(true, $array);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}
?>