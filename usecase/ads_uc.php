<?php 
include_once('../helper/import.php');

function createAds($body) {
    try {
        $conn = callDb();
        $currentDate = currentTime();
        $id = uniqid();

        $sql = "INSERT INTO ads (
            `ads_id`,
            `warung_id`,
            `name`,
            `description`,
            `image_id`,
            `status`,
            `start_date`,
            `end_date`,
            `created_at`,
            `updated_at`,
            `deleted_at`
            ) VALUES (
                '$id',
                '$body->warungId',
                '$body->name',
                '$body->description',
                '$body->imageId',
                '$body->status',
                '$body->startDate',
                '$body->endDate',
                '$currentDate',
                '$currentDate',
                ''
            )";
        $conn->query($sql);
        return resultBody(true);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function getAdsAll($status = NULL) {
    try {
        $conn = callDb();
        $array = array();

        $sql = "SELECT 
        f.file_name,
        w.name as warung_name,
        a.*
        FROM `ads` a
        LEFT JOIN `file` f ON a.image_id = f.file_id 
        LEFT JOIN `warung` w ON a.warung_id = w.warung_id
        WHERE a.deleted_at = '' 
        AND w.deleted_at = ''";

        if (!empty($status)) {
            $sql = $sql.", AND `a.status` = '$status'";
        }
        
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $data = new stdClass();
            $data->id = $row['ads_id'];
            $data->name = $row['name'];
            $data->description = $row['description'];
            $data->status = $row['status'];
            $data->startDate = $row['start_date'];
            $data->endDate = $row['end_date'];
            $data->imageId = $row['image_id'];
            $data->imageUrl = "";
            if (!empty($row['file_name'])) {
                $data->imageUrl = urlPathImage()."".$row["file_name"];
            }
            $warung = new stdClass();
            $warung->id = $row['warung_id'];
            $warung->name = $row['warung_name'];
            $data->warung = $warung;
            array_push($array, $data);
        }
        return resultBody(true, $array);
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return resultBody();
    }
}

function updateAds($bodyRequest) {
    try {
        clearstatcache();
        $conn = callDb();
        $updatedAt = currentTime();
        $adsId = $bodyRequest['id'] ?? '';

        $sql = "UPDATE ads SET `updated_at` = '$updatedAt'";
        
        if (!empty($bodyRequest['name'])) {
            $name = $bodyRequest['name'];
            $sql = $sql.", `name` = '$name'";
        }

        if (!empty($bodyRequest['description'])) { 
            $description = $bodyRequest['description'];
            $sql = $sql.", `description` = '$description'";
        }

        if (!empty($bodyRequest['startDate'])) { 
            $startDate = $bodyRequest['startDate'];
            $sql = $sql.", `start_date` = '$startDate'";
        }

        if (!empty($bodyRequest['endDate'])) { 
            $endDate = $bodyRequest['endDate'];
            $sql = $sql.", `end_date` = '$endDate'";
        }

        if (!empty($bodyRequest['status'])) {
            $status = $bodyRequest['status'];
            $sql = $sql.", `status` = '$status'";
        }

        if (!empty($bodyRequest['imageId'])) {
            $imageId = $bodyRequest['imageId'];
            $sql = $sql.", `image_id` = '$imageId'";
        }

        /// QUERY DELETE ADS
        if (!empty($bodyRequest['deleted']) && $bodyRequest['deleted'] == 'true') {
            $sql = $sql.", `deleted_at` = '$updatedAt'";
        }

        /// QUERY RE-ACTIVATE ADS
        if (!empty($bodyRequest['activated']) && $bodyRequest['activated'] == 'true' ) {
            $sql = $sql.", `deleted_at` = ''";
        }

        $sql = $sql." WHERE `ads_id`= '$adsId'";
        $conn->query($sql);
        return true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        response(500, $error);
        return false;
    }
}
?>