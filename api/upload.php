<?php
include_once('../helper/import.php');

try {
    clearstatcache();
    if(requestMethod() == "POST") {
        $dToken = validateToken(headerToken());
        if ($dToken != NULL) {
            if(isset($_FILES["file"]) && $_FILES["file"]["error"] == 0 && isset($_POST["type"])) {
                $typeImage = $_POST["type"];
                if ($typeImage == "profile" || $typeImage == "product" || $typeImage == "warung") {
                    $allowed = array("jpg" => "file/jpg", "jpeg" => "file/jpeg", "gif" => "file/gif", "png" => "file/png");
                    $filename = $_FILES["file"]["name"];
                    $filetype = $_FILES["file"]["type"];
                    $filesize = $_FILES["file"]["size"];
                    $filetemp = $_FILES["file"]["tmp_name"];

                    // verify file extension
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                    // set max size image uploaded
                    $maxsize = 2 * 1024 * 1024;
                    if(!array_key_exists($ext, $allowed)) {
                        response(400, "Format image tidak diizinkan");
                    } else if ($filesize > $maxsize) {
                        response(400, "Size image terlalu besar");
                    } else {
                        // image env
                        $server_url = urlPathImage();
                        $target_dir = "../uploads/";
                        $target_file = $target_dir . basename($filename);

                        /* create new name file */
                        $newfilename   = uniqid() ."". time();
                        $extension  = pathinfo($filename, PATHINFO_EXTENSION );
                        $basename   = $newfilename . "." . $extension;
                        $new_target_file = $target_dir . basename($basename);

                        if (move_uploaded_file($filetemp, $new_target_file)) {
                            $dFile = createFile($dToken->userId, $_POST["type"], $basename);
                            if ($dFile != NULL) {
                                $result = array(
                                    "id" => $dFile->id,
                                    "fileUrl" => urlPathImage()."".$basename,
                                    "fileName" => $basename,
                                    "type" => $dFile->type,
                                    "createdAt" => $dFile->createdAt
                                );
                                response(200, "Berhasil upload file", $result);
                            }
                        } else {
                            response(500, "Failed moved file");        
                        }
                    }
                } else {
                    response(500, "Type file not allowed");
                }
            } else {
                response(400, "File Not Found");
            }
        }
    } else {
        response(500);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    response(500, "Upload image : $error");
}

?>