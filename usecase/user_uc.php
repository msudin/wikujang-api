<?php
include_once('../helper/import.php');

function createUserRegister($bodyRequest) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $sql = "INSERT INTO user (
            `fullname`,
            `username`,
            `email`,
            `password`,
            `phone`,
            `birthdate`,
            `gender`,
            `active`,
            `role`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                 '$bodyRequest->fullName',
                 '$bodyRequest->userName',
                 '$bodyRequest->email',
                 '$bodyRequest->password',
                 '$bodyRequest->phone',
                 '$bodyRequest->birthdate',
                 '$bodyRequest->gender',
                 'true',
                 'user',
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

function getUserByPhone($phone) {
    try {
        $connn = callDb();
        $sql = "SELECT * FROM user WHERE phone=$phone";
        $result = $connn->query($sql);
        if ($result->num_rows == 1) {
            while($row = $result->fetch_assoc()) {
                $data = new \stdClass();
                $data->id = (int)$row["user_id"];
                $data->phone = $row["phone"];
                $data->password = $row["password"];
                $data->isActive = filter_var($row['active'], FILTER_VALIDATE_BOOLEAN);
                $data->role = $row['role'];
                $data->fullName = $row['fullname'];
                $data->userName = $row['username'];
                return $data;
            }
        } else {
            response(404, "No Hp belum terdaftar");
            return NULL;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return NULL;
    }
}

function getUserById($userId) {
    try {
        $connn = callDb();
        $server_url = urlPathImage();

        $sql = "SELECT f.file_name, f.type, ad.address_id, ad.subdistrict_id, ad.district_id, ad.address_detail, u.* 
        FROM `user` u 
        LEFT JOIN `file` f ON u.image_id = f.file_id 
        LEFT JOIN `address` ad ON u.address_id = ad.address_id 
        WHERE u.user_id=$userId";

        $result = $connn->query($sql);
        if ($result->num_rows == 1) {
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = (int)$row["user_id"];
                $data->email = $row["email"];
                $data->phone = $row["phone"];
                $data->fullName = $row["fullname"];
                $data->userName = $row["username"];
                $data->birthdate = $row['birthdate'];
                $data->gender = $row['gender'];
                $data->profileImage = NULL;
                if (!empty($row["image_id"])) {
                    $photo = new stdClass();
                    $photo->id = $row["image_id"];
                    $photo->fileUrl = urlPathImage()."".$row['file_name'];
                    $photo->fileName = $row['file_name'];
                    $data->profileImage = $photo;
                }
                $data->address = NULL;
                if (!empty($row["address_id"])) {
                    // query get detail Address;
                    $resultAddress = getAddressDetail($row["address_id"]);
                    if ($resultAddress->success = true) {
                        $data->address = $resultAddress->data;
                    } 
                }
                $data->isActive = filter_var($row['active'], FILTER_VALIDATE_BOOLEAN);
                $data->role = $row['role'];
                return $data;
            }
        } else {
            response(404);
            return NULL;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
        return NULL;
    }
}

function updateUserPhotoProfile($bodyRequest) {
    try {
        $conn = callDb();
        $updatedAt = currentTime();
        $sql = "UPDATE user SET
            `image_id`= '$bodyRequest->fileId',
            `updated_at` = '$updatedAt'
        WHERE `user_id`= $bodyRequest->userId";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
        return false;
    }
}

function updateUserRole($bodyRequest) {
    try {
        $conn = callDb();
        $updatedAt = currentTime();
        $sql = "UPDATE user SET
            `role`= '$bodyRequest->role',
            `updated_at` = '$updatedAt'
        WHERE `user_id`= $bodyRequest->userId";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, "$error");
        return false;
    }
}

function updatePassword($userId, $password) {
    try {
        $conn = callDb();
        $updatedAt = currentTime();
        $sql = "UPDATE user SET
            `password`= '$password',
            `updated_at` = '$updatedAt'
        WHERE `user_id`= $userId";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function forgotPassword($bodyRequest) {
    try {
        $conn = callDb();
        $updatedAt = currentTime();
        $password = $bodyRequest['newPassword'];
        $phone = $bodyRequest['phone'];

        $isSuccess = checkUserExistByPhone($phone);
        if ($isSuccess) {
            $sql = "UPDATE user SET
                `password`= '$password',
                `updated_at` = '$updatedAt'
                WHERE `phone`= '$phone'";
            $conn->query($sql);
            return true;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function updateProfile($bodyRequest, $userId) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();
        $addressId = $bodyRequest['addressId'] ?? NULL;

        $sql = "UPDATE user SET `updated_at` = '$updatedAt'";
        if (!empty($bodyRequest['fullName'])) {
            $fullName = $bodyRequest['fullName'];
            $sql = $sql.", `fullname` = '$fullName'";
        }

        if (!empty($bodyRequest['userName'])) {
            $userName = $bodyRequest['userName'];
            $sql = $sql.", `username` = '$userName'";
        }

        if (!empty($bodyRequest['gender'])) {
            $gender = $bodyRequest['gender'];
            $sql = $sql.", `gender` = '$gender'";
        }

        if (!empty($bodyRequest['birthdate'])) {
            $birthdate = $bodyRequest['birthdate'];
            $sql = $sql.", `birthdate` = '$birthdate'";
        }

        if (!empty($bodyRequest['imageId'])) {
            $imageId = $bodyRequest['imageId'];
            $sql = $sql.", `image_id` = '$imageId'";
        }

        if (!empty($bodyRequest['email'])) {
            $email = $bodyRequest['email'];
            $sql = $sql.", `email` = '$email'";
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

        $sql = $sql." WHERE `user_id`= $userId";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}

function getUserPhoneById($id) {
    try {
        $connn = callDb();
        $sql = "SELECT phone FROM user WHERE user_id=$id";
        $result = $connn->query($sql);
        if ($result->num_rows == 1) {
            while($row = $result->fetch_assoc()) {
                $data = new \stdClass();
                $data->phone = $row["phone"];
                return $data;
            }
        } else {
            response(404);
            return NULL;
        }
    } catch (Exception $e) {
        return NULL;
    }
}


function getAllUser() {
    try {
        $connn = callDb();
        $array = array();

        $sql = "SELECT * FROM user";
        $result = $connn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new \stdClass();
            $data->id = (int) $row["user_id"];
            $data->phone = $row["phone"];
            $data->fullName = $row["fullname"];
            $data->userName = $row["username"];
            $data->password = $row["password"];
            $data->email = $row["email"];
            $data->role = $row["role"];
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
?>