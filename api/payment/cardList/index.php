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
    $json = array("success" => 0, "result" => array(), "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => array(), "error" => "Usuario Inválido");
    } else {
        $getUserToekn = get_user_meta($userID, "requestId", true);
        if ($getUserToekn == "") {
            $json = array("success" => 0, "result" => array(), "error" => "Sin tarjeta añadida");
        } else {
 $requestId = json_decode($getUserToekn);
            $getType = gettype($requestId);
            if ($getType == "integer") {
                $requestId = (array)$requestId;
            } 
            $selectedCard = get_user_meta($userID, "selectedCard", true);
            foreach ($requestId as $key => $value) {

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
                    if($selectedCard == $value) {
                        $def = 1;
                    } else {
                        $def = 0;
                    }

                    $tokenCard = $result->subscription->instrument[0]->value;
                    $cardNumber = $result->subscription->instrument[5]->value;
                    $cardType = $result->subscription->instrument[3]->value;
                    $cardExpire = $result->subscription->instrument[6]->value;
                    $cardDetails[] = array("requestId" => "$value", "token" => $tokenCard, "cardNumber" => $cardNumber, "cardType" => $cardType, "defaultCard" => $def, "cardExpire" => $cardExpire, "message" => "No hay error");

                }
            }
        }
        if(empty($cardDetails)) {
            $json = array("success" => 0, "result" => array(), "error" => "Aún no tienes ningún método de pago registrado. Gracias");
        } else {
            $json = array("success" => 1, "result" => $cardDetails, "error" => "No se ha encontrado ningún error");
        }

    }
}
echo json_encode($json);
?>