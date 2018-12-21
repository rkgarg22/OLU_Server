<?php
include("../../../wp-config.php");
$userID = $_GET['userID'];
$requestIdNew = $_GET['requestId'];
$login = "0204631fd5dfd6ff86fb92b2eef67e3f";
$seed = date('c');
$generateMyRefNumber = generateMyRefNumber();
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
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
		  $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://secure.placetopay.com/redirection/api/session/" . $requestIdNew);
            curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            $result1 = curl_exec($ch);
            $result1 = json_decode($result1);
            curl_close($ch);
			if($result1->status->status == "APPROVED") {
				
				$requestId = get_user_meta($userID , "requestId" , true);
				$requestId = json_decode($requestId);
				$getType = gettype($requestId);
				if($getType == "integer" || $requestId == "") {
					$requestId = (array)$requestId;
					$requestId[] = $requestIdNew;
				} else {
					$requestId[] = $requestIdNew;
				}
				update_user_meta($userID , "requestId" , json_encode($requestId));
				$json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
			} elseif($result1->status->status == "PENDNING") {
				$json = array("success" => 1, "result" => 1, "error" => "En este momento su SOLICITUD presenta un proceso de pago cuya transacción se encuentra PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde para verificar si su pago fue confirmado de forma exitosa. Si desea mayor información sobre el estado actual de su operación puede comunicarse a nuestras líneas de atención al cliente o enviar un correo electrónico a hola@olu.co y preguntar por el estado de la transacción.");
			} elseif($result1->status->status == "REJECTED") {
				$json = array("success" => 1, "result" => 1, "error" => "El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información. Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago. Muchas gracias");
			}
    }
}
echo json_encode($json);
