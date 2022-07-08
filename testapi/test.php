<?php 

include_once('../config/response.php');
include_once('../config/http.php');

try {
    if (requestMethod() == 'GET') {
        $token = headerToken();
        if (isset($token)) {
            response(200, "", array("token" => $token));
        } else {
            if(!empty($_GET["id"])) {
                $id = intval($_GET["id"]);
                response(200, "key => id : $id");
            } else {
                $key = "CNtVA26CRRqdd293H5rdWq81Opa4UCi2GEYyulTV0zAjIytTDQ86vTvAExKqhU9P";
                response(200, "key : $key");
            }
        }
    } else {
        response(500, "method not allowed");
    }
} catch (Exception $e) {
    response(500, "method not allowed");
}

?>