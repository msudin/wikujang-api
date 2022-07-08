<?php

function response($code = 500, $msg = "", $data = null) {

    // mapping message
    if ($code == 200) {
        if ($msg == "") {
            $msg = "record found";
        }
    } else if ($code == 401) {
        $msg = "Unauthorized";
        $data = null;
    } else if ($code == 400) {
        if ($msg == "") {
            $msg = "record not found";
        }
    } else if ($code == 500) {
        if ($msg == "") {
            $msg = "internal server error";
        }
    } else if ($code == 404) {
        if ($msg == "") {
            $msg = "record not found";
        }
    }

    // show [echo] json api
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    $result = new \stdClass();
    $result->code = $code;
    $result->status = $code;
    $result->message = $msg;
    $result->data = $data;
    $myJSON = json_encode($result);
    echo $myJSON;
}

function resultBody($isSuccess = false, $data = null) {
    $result = new stdClass();
    $result->success = $isSuccess;
    $result->data = $data;
    return $result;
}

?>