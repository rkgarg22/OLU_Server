<?php
    include("../../../wp-config.php");
    $userID = $_GET['userID'];
    $login = "0204631fd5dfd6ff86fb92b2eef67e3f";
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
$tranKey = base64_encode(sha1($nonce . $seed . "i0619XM418y6Pc82", true));
if ($userID == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => null, "error" => "Usuario Inválido");
    } else {
        $getUserToekn = get_user_meta($userID , "requestId" , true);
        if($getUserToekn == "") {

            $generateMyRefNumber = generateMyRefNumber();
            $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" },"subscription" : { "reference": "' . $generateMyRefNumber . '", "description": "Pago básico de prueba", "fields" : [] },"expiration": "' . $nextmonth . '", "returnUrl": "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/payment/return/", "ipAddress": "127.0.0.1", "userAgent": "PlacetoPay Sandbox"}';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://secure.placetopay.com/redirection/api/session/");
            curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            $result = curl_exec($ch);
            $result = json_decode($result);
            if($result->status->status == "OK") {
                $requestId  = $result->requestId;
                $processURL = $result->processUrl;
                $message = "No hay error";
                update_user_meta($userID , "requestId" , $requestId);
            } else {
                $requestId = "";
                $processURL = "";
                $message = "Error";
            }
            $genToken = array("requestId" => "$requestId" , "processURL" => $processURL , "message" => $message);
           $token = 0;
            $cardDetails = array();
            // $cardDetails[] = array("token" => "", "cardNumber" => "", "cardType" => "", "cardExpire" => "" , "message" => "No Data");
        } else {

            $selectedCard = get_user_meta($userID, "selectedCard", true);
           /*  $requestId = json_decode($getUserToekn);
            $getType = gettype($requestId);
            if ($getType == "integer") {
                $requestId = (array)$requestId;
            }  */
            $reqID = (array)$selectedCard;
            foreach ($reqID as $key => $value) {
                $genToken = array("requestId" => "", "processURL" => "", "message" => "No Data");
                $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://secure.placetopay.com/redirection/api/session/" . $value);
                curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                $result = curl_exec($ch);
                $result = json_decode($result);
                $token = 1;
                if ($result->status->status == "APPROVED") {
                    $tokenCard = $result->subscription->instrument[0]->value;
                    $cardNumber = $result->subscription->instrument[5]->value;
                    $cardType = $result->subscription->instrument[3]->value;
                    $cardExpire = $result->subscription->instrument[6]->value;
                    $cardDetails[] = array("requestId" => $value , "token" => $tokenCard, "cardNumber" => $cardNumber, "cardType" => $cardType, "cardExpire" => $cardExpire, "message" => "No hay error");

                }
            }
            if(empty($cardDetails)) {
                $generateMyRefNumber = generateMyRefNumber();
                $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" },"subscription" : { "reference": "' . $generateMyRefNumber . '", "description": "Pago básico de prueba", "fields" : [] },"expiration": "' . $nextmonth . '", "returnUrl": "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/payment/return/", "ipAddress": "127.0.0.1", "userAgent": "PlacetoPay Sandbox"}';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://secure.placetopay.com/redirection/api/session/");
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
                    update_user_meta($userID, "requestId", $requestId);
                } else {
                    $requestId = "";
                    $processURL = "";
                    $message = "Error";
                }
                $genToken = array("requestId" => "$requestId", "processURL" => $processURL, "message" => $message);
                $tokenCard = "";
                $cardNumber = "";
                $cardType = "";
                $cardExpire = "";
                $cardDetails = array();
                //$cardDetails[] = array("token" => $tokenCard, "cardNumber" => $cardNumber, "cardType" => $cardType, "cardExpire" => $cardExpire , "message" => "request id not approved, try again later");
                update_user_meta($userID, "requestId", "");
                $token = 0;
            }
            // $genToken = array();
        }
        $data = array("isTokenAvailable" => $token , "processUrl" => $genToken , "cardDetails" => $cardDetails);
        $json = array("success" => 1, "result" => $data, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);

?>