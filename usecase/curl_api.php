<?php
include_once('../helper/import.php');

function callAPI($method, $url, $data) {
   $curl = curl_init();
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_FAILONERROR, true);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

   if (isEnvironmentLocal()) { 
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Authorization: Basic eG5kX2RldmVsb3BtZW50X3g3dFgxVWp2eUNJVzd5aVhycDJEWk1KWG9JWDJRUnRvWmZKWnRZaU14Z05RMXhHMXQxVjZpY1lhbHJvQ3VCOg==',
      'Content-Type: application/json'));
   } else {
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
         'Authorization: Basic eG5kX2RldmVsb3BtZW50X3ZCVkxDYm9xUnB4SndCZFRQZjd0RnhJVzZqUUFIWlVoUGZDWnhNY1BSeXNnVFNrSjdXQk15Mmc0cmU0TlhlOg==',
         'Content-Type: application/json'));
   }

   // EXECUTE:
   $result = curl_exec($curl);
   if (curl_errno($curl)) {
      // $curl_error = curl_error($curl);
      // echo $curl_error;
      curl_close($curl);
      return NULL;
   } else {
      $info = curl_getinfo($curl);
      curl_close($curl);
      if ($info["http_code"] == 200 || $info["http_code"] == '200') {
         return json_decode($result);
      } else {
         return NULL;
      }
   }
}
?>