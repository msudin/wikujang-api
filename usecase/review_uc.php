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
        if ($result == 1) {
            $dRatingProduct = getAverageRatingProduct($body->productId);
            if ($dRatingProduct->success) {
                // return resultBody(true, $dRatingProduct->data);
                return resultBody(true);
            }
        }
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
        // $error = $e->getMessage();
        // response(500, $error);
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
        // $error = $e->getMessage();
        // response(500, $error);
        return resultBody();
    }
}
?>