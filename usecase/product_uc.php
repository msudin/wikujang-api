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
            `category_id`,
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
                '$body->categoryId',
                $body->price,
                '$body->imageId',
                0,
                0,
                $body->discountAmount,
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

function bulkRatingByMenuId($menuId) {
    $dRating = getAverageRatingProduct($menuId);
    $entityData = ['rating' => $dRating->data, 'productId' => $menuId];
    updateProduct($entityData);
}

function bulkRatingMenu() {
    try {
        $conn = callDb();
        $sql = "SELECT `product_id` FROM product";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            bulkRatingByMenuId($productId);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
    }
}

function getProductAll(
        $limit = 0,
        $warungId = NULL, 
        $name = NULL, 
        $minPrice = 0,
        $maxPrice = 1000000000,
        $categoryId = NULL, 
        $views = NULL,
        $price = NULL, 
        $rating = NULL, 
        $sort = NULL
    ) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.file_name, c.category_name, w.latitude, w.longitude, p.* 
        FROM `product` p 
        LEFT JOIN `file` f ON p.image_id = f.file_id 
        LEFT JOIN `category` c ON p.category_id = c.category_id
        LEFT JOIN `warung` w ON p.warung_id = w.warung_id 
        WHERE w.deleted_at = '' AND p.deleted_at = ''";

        if (!empty($name)) {
            $sql = $sql." AND p.name LIKE '%$name%'";   
        }

        if (!empty($categoryId)) {
            $sql = $sql." AND p.category_id = '$categoryId'";   
        }

        $sql = $sql." AND p.price BETWEEN $minPrice AND $maxPrice";

        if (!empty($warungId)) {
            $sql = $sql." AND p.warung_id = '$warungId'";
        }

        if (!empty($sort)) {
            if ($sort == "desc") {
                $sql = $sql." ORDER BY p.created_at DESC";   
            } else {
                $sql = $sql." ORDER BY p.created_at ASC";   
            }
        } else {
            $isFilterByViews = false;
            if (!empty($views)) {
                $isFilterByViews = true;
                if ($views == "desc") {
                    $sql = $sql." ORDER BY p.views DESC";   
                } else {
                    $sql = $sql." ORDER BY p.views ASC";   
                }
            }

            $isFilterByPrice = false;
            if (!empty($price)) {
                $isFilterByPrice = true;
                if ($isFilterByViews) {
                    if ($price == "desc") {
                        $sql = $sql.", p.price DESC";   
                    } else {
                        $sql = $sql.", p.price ASC";   
                    }
                } else {
                    if ($price == "desc") {
                        $sql = $sql." ORDER BY p.price DESC";   
                    } else {
                        $sql = $sql." ORDER BY p.price ASC";   
                    }
                }
            }

            if (!empty($rating)) {
                if ($isFilterByViews || $isFilterByPrice) {
                    if ($rating == "desc") {
                        $sql = $sql.", p.rating DESC";   
                    } else {
                        $sql = $sql.", p.rating ASC";   
                    }
                } else {
                    if ($rating == "desc") {
                        $sql = $sql." ORDER BY p.rating DESC";   
                    } else {
                        $sql = $sql." ORDER BY p.rating ASC";   
                    }
                }
            }
        }
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
            $data->price = (int) $row['price'];
            $data->discountAmount = (int) $row['discount_amount'];
            $data->rating = (double) $row['rating'];
            $data->likes = (int) $row['likes'];
            $data->views = (int) $row['views'];
            $data->rating = (double) $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                if (isset($row["file_name"])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
            }
            $data->category = null;
            if (!empty($row['category_name'])) {
                $category = new stdClass();
                $category->id = $row['category_id'];
                $category->name = $row['category_name'];
                $data->category = $category;
            }
            $dRatingProduct = getAverageRatingProduct($data->id);
            if ($dRatingProduct->success) {
                $data->rating = $dRatingProduct->data;
            }
            $data->latitude = $row['latitude'];
            $data->longitude = $row['longitude'];
            $data->createdAt = $row['created_at'];
            array_push($array, $data);
        }
        return resultBody(true, $array);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getProductMe($warungId) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.file_name, c.category_name, w.latitude, w.longitude, p.*
        FROM `product` p 
        LEFT JOIN `file` f ON p.image_id = f.file_id
        LEFT JOIN `category` c ON p.category_id = c.category_id
        LEFT JOIN `warung` w ON p.warung_id = w.warung_id
        WHERE p.warung_id = '$warungId' AND p.deleted_at = '' AND w.deleted_at = ''";

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->id = $row['product_id'];
            $data->warungId = $row['warung_id'];
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->price = (int) $row['price'];
            $data->discountAmount = (int) $row['discount_amount'];
            $data->rating = (double) $row['rating'];
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
            $data->category = null; 
            if (!empty($row['category_name'])) {
                $category = new stdClass();
                $category->id = $row['category_id'];
                $category->name = $row['category_name'];
                $data->category = $category;
            }
            $dRatingProduct = getAverageRatingProduct($data->id);
            if ($dRatingProduct->success) {
                $data->rating = $dRatingProduct->data;
            }
            $data->latitude = $row['latitude'];
            $data->longitude = $row['longitude'];
            $data->createdAt = $row['created_at'];
            array_push($array, $data);
        }
        return resultBody(true, $array);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
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

        $sql = "SELECT f.file_name, c.category_name, p.*
        FROM `product` p
        LEFT JOIN `file` f ON p.image_id = f.file_id
        LEFT JOIN `category` c ON p.category_id = c.category_id
        WHERE product_id = '$productId'";

        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data->id = $row['product_id'];
            $data->warungId = $row['warung_id'];
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->price = (int) $row['price'];
            $data->rating = (double) $row['rating'];
            $data->discountAmount = (int) $row['discount_amount'];
            $data->likes = (int) $row['likes'];
            $data->views = (int) $row['views'];
            $data->rating = (double) $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                if (isset($row["file_name"])) {
                    $data->imageUrl = urlPathImage()."".$row["file_name"];
                }
            }
            $data->category = null;
            if (!empty($row['category_name'])) {
                $category = new stdClass();
                $category->id = $row['category_id'];
                $category->name = $row['category_name'];
                $data->category = $category;
            }
            $data->warung = null;
            $dWarung = getWarungById($data->warungId);
            if ($dWarung->success) {
                $data->warung = $dWarung->data;
            }
            $dRatingProduct = getAverageRatingProduct($data->id);
            if ($dRatingProduct->success) {
                $data->rating = $dRatingProduct->data;
            }
        }
        return resultBody(true, $data);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function deleteProduct($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $sql = "UPDATE `product` SET `deleted_at` = '$currentDate' WHERE `product_id` = '$bodyRequest->id'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function updateProduct($bodyRequest) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();

        $sql = "UPDATE `product` SET `updated_at` = '$updatedAt'";
        
        if (!empty($bodyRequest['name'])) {
            $name = $bodyRequest['name'];
            $sql = $sql.", `name` = '$name'";
        }

        if (!empty($bodyRequest['description'])) { 
            $description = $bodyRequest['description'];
            $sql = $sql.", `description` = '$description'";
        }

        if (!empty($bodyRequest['categoryId'])) { 
            $category = $bodyRequest['categoryId'];
            $sql = $sql.", `category_id` = '$category'";
        }

        if (!empty($bodyRequest['price'])) { 
            $price = $bodyRequest['price'];
            $sql = $sql.", `price` = $price";
        }

        if (!empty($bodyRequest['imageId'])) {
            $imageId = $bodyRequest['imageId'];
            $sql = $sql.", `image_id` = '$imageId'";
        }

        if (!empty($bodyRequest['rating'])) {
            $rating = $bodyRequest['rating'];
            $sql = $sql.", `rating` = $rating";
        }

        if (!empty($bodyRequest['discountAmount'])) {
            $discountAmount = $bodyRequest['discountAmount'];
            $sql = $sql.", `discount_amount` = '$discountAmount'";
        }

        /// QUERY RE-ACTIVATE Product
        if (!empty($bodyRequest['activated']) && $bodyRequest['activated'] == true ) {
            $sql = $sql.", `deleted_at` = ''";
        }

        $productId = $bodyRequest['productId'];
        $sql = $sql." WHERE `product_id`= '$productId'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}
?>