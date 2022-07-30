<?php
include_once('../helper/import.php');

function createWarung($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();

        $result = createAddress($body->subDistrictId, $body->districtId, $body->address);
        if ($result->success == true) {
            $sql = "INSERT INTO warung (
                `warung_id`,
                `user_id`,
                `name`,
                `username`,
                `description`,
                `is_open`,
                `open_time`,
                `closed_time`,
                `rating`,
                `views`,
                `image_id`,
                `address_id`,
                `latitude`,
                `longitude`,
                `created_at`,
                `updated_at`,
                `deleted_at`
                ) VALUES (
                    '$body->id',
                    $body->userId,
                    '$body->name',
                    '$body->userName',
                    '$body->description',
                    '$body->isOpen',
                    '$body->openTime',
                    '$body->closedTime',
                    $body->rating,
                    $body->views,
                    '$body->imageId',
                    '$result->data',
                    '$body->latitude',
                    '$body->longitude',
                    '$currentDate',
                    '$currentDate',
                    ''
                )";
            $conn->query($sql);
            return true;
        }
        return false;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
        return false;
    }
}

function getAllWarung() {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT f.file_name, w.*
        FROM `warung` w
        LEFT JOIN `file` f ON w.image_id = f.file_id WHERE w.deleted_at=''";
        
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->id = $row['warung_id'];
            $data->userId = (int)$row['user_id'];
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->isOpen = filter_var($row['is_open'], FILTER_VALIDATE_BOOLEAN);
            $data->openTime = $row['open_time'];
            $data->closedTime = $row['closed_time'];
            $data->rating = $row['rating'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                $data->imageUrl = urlPathImage()."".$row["file_name"];
            }
            $data->address = null;
            $resultAddress = getAddressDetail($row['address_id']);
            if ($resultAddress->success = true) {
                $data->address = $resultAddress->data;
            }
            $data->latitude = $row['latitude'];
            $data->longitude = $row['longitude'];
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

function getWarungByUserId($id = NULL) {
    try {
        $conn = callDb();
        $data = new stdClass();
        $result = new stdClass();
        $temp = new stdClass();

        $sql = "SELECT f.file_name, w.*
        FROM `warung` w
        LEFT JOIN `file` f ON w.image_id = f.file_id 
        WHERE w.user_id = $id AND w.deleted_at = ''";
        $result = $conn->query($sql);
        
        while($row = $result->fetch_assoc()) {
            $data->id = $row['warung_id'];
            $data->userId = (int)$row['user_id'];
            if (!empty($data->userId)) {
                $dProfile = getUserPhoneById($data->userId);
                $data->phone = $dProfile->phone;
            }
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->isOpen = filter_var($row['is_open'], FILTER_VALIDATE_BOOLEAN);
            $data->openTime = $row['open_time'];
            $data->closedTime = $row['closed_time'];
            $data->rating = $row['rating'];
            $data->views = (int)$row['views'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                $data->imageUrl = urlPathImage()."".$row["file_name"];
            }
            $data->address = null;
            $temp->addressId = $row['address_id'];
            if (!empty($temp->addressId)) {
                $resultAddress = getAddressDetail($temp->addressId);
                if ($resultAddress->success = true) {
                    $data->address = $resultAddress->data;
                } else {
                    return;
                }   
            } 
            $data->latitude = $row['latitude'];
            $data->longitude = $row['longitude'];
            return resultBody(true, $data);
        } 
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getWarungById($id) {
    try {
        $conn = callDb();
        $data = new stdClass();
        $result = new stdClass();
        $temp = new stdClass();

        $sql = "SELECT f.file_name, w.*
        FROM `warung` w
        LEFT JOIN `file` f ON w.image_id = f.file_id WHERE warung_id = '$id'";
        $result = $conn->query($sql);
        
        while($row = $result->fetch_assoc()) {
            $data->id = $row['warung_id'];
            $data->userId = (int)$row['user_id'];
            if (!empty($data->userId)) {
                $dProfile = getUserPhoneById($data->userId);
                $data->phone = $dProfile->phone;
            }
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->isOpen = filter_var($row['is_open'], FILTER_VALIDATE_BOOLEAN);
            $data->openTime = $row['open_time'];
            $data->closedTime = $row['closed_time'];
            $data->rating = $row['rating'];
            $data->views = (int)$row['views'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($data->imageId)) {
                $data->imageUrl = urlPathImage()."".$row["file_name"];
            }
            $data->address = null;
            $temp->addressId = $row['address_id'];
            if (!empty($temp->addressId)) {
                $resultAddress = getAddressDetail($temp->addressId);
                if ($resultAddress->success = true) {
                    $data->address = $resultAddress->data;
                } else {
                    return;
                }   
            } 
            $data->latitude = $row['latitude'];
            $data->longitude = $row['longitude'];
            return resultBody(true, $data);
        } 
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}


function updateWarung($bodyRequest, $warungId) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();
        $addressId = $bodyRequest['addressId'] ?? NULL;

        $sql = "UPDATE warung SET `updated_at` = '$updatedAt'";
        
        if (!empty($bodyRequest['name'])) {
            $name = $bodyRequest['name'];
            $sql = $sql.", `name` = '$name'";
            $sql = $sql.", `username` = '$name'";
        }

        if (!empty($bodyRequest['description'])) { 
            $description = $bodyRequest['description'];
            $sql = $sql.", `description` = '$description'";
        }

        if (!empty($bodyRequest['openTime'])) { 
            $openTime = $bodyRequest['openTime'];
            $sql = $sql.", `open_time` = '$openTime'";
        }

        if (!empty($bodyRequest['closedTime'])) { 
            $closedTime = $bodyRequest['closedTime'];
            $sql = $sql.", `closed_time` = '$closedTime'";
        }

        if (!empty($bodyRequest['longitude'])) {
            $longitude = $bodyRequest['longitude'];
            $sql = $sql.", `longitude` = '$longitude'";
        }

        if (!empty($bodyRequest['latitude'])) {
            $latitude = $bodyRequest['latitude'];
            $sql = $sql.", `latitude` = '$latitude'";
        }

        if (!empty($bodyRequest['views'])) {
            $views = $bodyRequest['views'];
            $sql = $sql.", `views` = $views";
        }

        if (!empty($bodyRequest['rating'])) {
            $rating = $bodyRequest['rating'];
            $sql = $sql.", `rating` = $rating";
        }

        if (!empty($bodyRequest['imageId'])) {
            $imageId = $bodyRequest['imageId'];
            $sql = $sql.", `image_id` = '$imageId'";
        }

        if (!empty($bodyRequest['subDistrictId'])) {
            $subDistrictId = $bodyRequest['subDistrictId']; 
            $districtId = $bodyRequest['districtId']; 
            $addressDetail = $bodyRequest['address']; 
            if (!empty($addressId)) {
                $resultAddress = updateAddress($addressId, $subDistrictId, $districtId, $addressDetail);
                if ($resultAddress->success == false) {
                    return false;
                }
             } else {
                 $resultAddress = createAddress($subDistrictId, $districtId, $addressDetail);
                 if ($resultAddress->success == true) {
                     $addressId = $resultAddress->data;  
                 } else {
                     return false;
                 }    
             }
        } 

        if (!empty($addressId)) {
            $sql = $sql.", `address_id` = '$addressId'";
        }


        /// QUERY DELETE WARUNG
        if (!empty($bodyRequest['deleted']) && $bodyRequest['deleted'] == true) {
            $sql = $sql.", `deleted_at` = '$updatedAt'";
        }

        /// QUERY RE-ACTIVATE WARUNG
        if (!empty($bodyRequest['activated']) && $bodyRequest['activated'] == true ) {
            $sql = $sql.", `deleted_at` = ''";
        }

        $sql = $sql." WHERE `warung_id`= '$warungId'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}
?>