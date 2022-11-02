<?php 

function requestMethod() {
    return $_SERVER["REQUEST_METHOD"] ?? NULL;
}

function headerToken() {
    $headers = apache_request_headers();
    $token = "";
    if (!empty($headers['Authorization'])) {
        $token = $headers['Authorization'];
    } else if (!empty($headers['authorization'])) {
        $token = $headers['authorization'];
    }
    return $token;
}

function headerTokenXendit() {
    $headers = getallheaders();
    $token = "";
    if (!empty($headers['Authorization'])) {
        $token = $headers['Authorization'];
    } else if (!empty($headers['authorization'])) {
        $token = $headers['authorization'];
    } else if (!empty($headers['X-CALLBACK-TOKEN'])) {
        $token = $headers['X-CALLBACK-TOKEN'];
    }
    return $token;
}

function isEnvironmentLocal() {
    return false;
}

function serverName() {
    return "localhost";
}

function serverUserName() {
    if (isEnvironmentLocal()) {
        return "root";
    } else {
        return "wiks7958_albaar";
    }
}

function serverDbPassword() {
    if (isEnvironmentLocal()) {
        return "root";
    } else {
        return "Albaar_1234";
    }
}

function serverDbName() {
    if (isEnvironmentLocal()) { 
        return "wikujangdb";
    } else {
        return "wiks7958_wikujang";
    }
}

function urlPathImage() {
    if (isEnvironmentLocal()) {
        return "http://192.168.68.249:8888/wikujang-api/"."uploads/"; 
    } else {
        return "https://wikujang.site/apiv1/uploads/";
    }
}

?>