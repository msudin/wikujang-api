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
            `comment`,
            `image_id`,
            `rating`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$id',
                '$body->productId',
                '$body->comment',
                '$body->imageId',
                $body->rating,
                '$currentDate',
                '$currentDate',
                ''
            )";
        $result = $conn->query($sql);
        if ($result == 1) {
            $dRating = getAverageRating($body->productId);
            if ($dRating->success) {
                return resultBody(true, $dRating->data);
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getAverageRating($dProductId) {
    try {
        $conn = callDb();
        $rating = 0;

        $sql = "SELECT AVG(`rating`) AS avg_rating FROM `review` WHERE `product_id` = '$dProductId' AND `deleted_at` = ''";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $temp = $row['avg_rating'];
            $rating = number_format((double)$temp, 2, '.', '');
        }
        return resultBody(true, $rating);
    } catch (Exception $e) {
        return resultBody();
    }
}
?>