<?php 
include_once('../helper/import.php');

function createAddress($subDistrictId = null, $districtId = null, $description = null) {
    try {
        if (isset($subDistrictId) && isset($districtId) && isset($description)) {
            $conn = callDb();
            $currentDate = currentTime();
            $addressId = uniqid();
            $sql = "INSERT INTO `address` (
                `address_id`,
                `subdistrict_id`, 
                `district_id`,
                `address_detail`,
                `created_at`,
                `updated_at`,
                `deleted_at`
                ) VALUES (
                    '$addressId',
                    $subDistrictId,
                    $districtId,
                    '$description',
                    '$currentDate',
                    '$currentDate',
                    ''
                )";
                $conn->query($sql);
                return resultBody(true, $addressId);
        } else {
            response(500);
            return resultBody();
        }
    } catch (Exception $e){
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getAddressDetail($addressId = null) {
    try {
        if (isset($addressId)) {
            $connn = callDb();
            $data = NULL;

            $sql = "SELECT * FROM `address` a
            LEFT JOIN `subdistrict` s ON a.subdistrict_id = s.subdistrict_id 
            LEFT JOIN `district` d ON a.district_id = d.district_id 
            WHERE a.address_id='$addressId'";

            $result = $connn->query($sql);
            while($row = $result->fetch_assoc()) {
                $data = new stdClass();
                $data->id = $row['address_id'];
                $data->subDistrictId = (Int) $row['subdistrict_id'];
                $data->subDistrictName = $row['subdistrict_name'];
                $data->districtId = (Int) $row['district_id'];
                $data->districtName = $row['district_name'];
                $data->postalCode = (Int) $row['postal_code'];
                $data->addressDetail = $row['address_detail'];
            }
            return resultBody(true, $data);
        } else {
            return resultBody();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function updateAddress($addressId, $subDistrictId, $districtId, $description) {
    try {
        $conn = callDb();
        $updatedAt = currentTime();
        $sql = "UPDATE `address` SET
            `subdistrict_id`= $subDistrictId,
            `district_id`= $districtId,
            `address_detail`= '$description',
            `updated_at`= '$updatedAt'
        WHERE `address_id`='$addressId'";
        $conn->query($sql);
        return resultBody(true);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}
?>