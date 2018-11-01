<?php
include("../../../wp-config.php");
$userID = $_GET['userID'];
$login = "fcec4c9fd9ea26079d9302b2424d38ea";
$seed = date('c');
if (function_exists('random_bytes')) {
    $nonce = bin2hex(random_bytes(16));
} elseif (function_exists('openssl_random_pseudo_bytes')) {
    $nonce = bin2hex(openssl_random_pseudo_bytes(16));
} else {
    $nonce = mt_rand();
}
$nonceBase64 = base64_encode($nonce);
$nextmonth = date('c', strtotime(' +1 month'));
$tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));
if ($userID == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => null, "error" => "Usuario Inválido");
    } else {
        $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" },"subscription" : { "reference": "' . rand(100000, 1000000) . '", "description": "Pago básico de prueba", "fields" : [] },"expiration": "' . $nextmonth . '", "returnUrl": "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/payment/return/", "ipAddress": "127.0.0.1", "userAgent": "PlacetoPay Sandbox"}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/session/");
        curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec($ch);
        $result = json_decode($result);
        if ($result->status->status == "OK") {
            $requestId = $result->requestId;
            $processURL = $result->processUrl;
            $message = "No hay error";
            // update_user_meta($userID, "requestId", $requestId);
        } else {
            $requestId = "";
            $processURL = "";
            $message = "Error";
        }
        $genToken = array("requestId" => $requestId, "processURL" => $processURL, "message" => $message);

        $json = array("success" => 1, "result" => $genToken, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
