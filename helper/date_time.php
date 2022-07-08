<?php 
date_default_timezone_set('Asia/Jakarta');

function currentTime() {
    return date("Y-m-d H:i:s");
}

function customTimeAdd($date, $day = 7) {
    $date = strtotime($date);
    $date = strtotime("+$day day", $date);
    return date('Y-m-d H:i:s', $date);
}
?>