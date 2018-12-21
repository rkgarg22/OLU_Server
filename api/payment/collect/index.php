<?php

date_default_timezone_set("America/Bogota");
$current = date("Y-m-d H:i:s");
include("../../../wp-config.php");
$userID = $_GET['userID'];
$token = $_GET['token'];
$login = "0204631fd5dfd6ff86fb92b2eef67e3f";
$planID = $_GET['planID'];
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
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido");
    } else {
        $getDataPending = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `user_id` = $userID ORDER BY `id` DESC LIMIT 1");

        if(empty($getDataPending) || $getDataPending[0]->payment_status != 2){
            
        //Defining Price
            if ($planID == 1) {
                $priceValue = "300000";
                $priceGet = 315;
            } elseif ($planID == 2) {
                $priceValue = "600000";
                $priceGet = 645;
            } else {
                $priceValue = "900000";
                $priceGet = 990;
            }
        //Defining Price


            $getUserToekn = get_user_meta($userID, "requestId", true);
            $first_name = get_user_meta($userID, "first_name", true);
            $last_name = get_user_meta($userID, "last_name", true);
            $requestId = json_decode($getUserToekn);
            $getType = gettype($requestId);
            if ($getType == "integer") {
                $requestId = (array)$requestId;
            }
            foreach ($requestId as $key => $value) {
                $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://secure.placetopay.com/redirection/api/session/" . $value);
                curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                $result1 = curl_exec($ch);
                $result1 = json_decode($result1);
                curl_close($ch);
                $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
                $txt = json_encode($result1);
                fwrite($myfile, $txt);
                fclose($myfile);
                if (!empty($result1->request->payer) && $result1->subscription->instrument[0]->value == $token && $result1->status->status == "APPROVED") {
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
                    $collectData = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" },  "instrument": { "token": { "token": "' . $token . '" } } , "payer" : ' . json_encode($result1->request->payer) . ',"buyer":{"document":"","documentType":"CC","name":"' . $first_name . '","surname":"' . $last_name . '","email":"' . $user->data->user_login . '","address":{"street":"","city":"","country":""}} , "payment": { "reference": "' . $generateMyRefNumber . '", "description": "Pago básico de prueba", "amount": { "currency": "COP", "total": "' . $priceValue . '" } }}';
                    $ch = curl_init();
                    $agents = array(
                        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'

                    );
                    curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);
                    curl_setopt($ch, CURLOPT_URL, "https://secure.placetopay.com/redirection/api/collect/");
                    curl_setopt($ch, CURLOPT_POST, count(json_decode($collectData)));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $collectData);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                    $result = curl_exec($ch);
                    $result = json_decode($result);
                    curl_close($ch);

                    if ($result->status->status == "APPROVED") {

                        $json = array("success" => 1, "result" => $result->requestId, "error" => "No se ha encontrado ningún error");
                        $wpdb->insert('wtw_add_money', array(
                            'user_id' => $userID,
                            'txn_id' => $result->requestId,
                            'moneyPlan' => $planID,
                            'moneyValue' => str_replace("00000", "00", $priceValue),
                            'moneyAdded' => $priceGet,
                            'created_date' => $current,
                            'payment_status' => 1,
                            'ref_num' => $generateMyRefNumber
                        ));
                        break;
                    } elseif ($result->status->status == "PENDING") {
                        $message = "En este momento su SOLICITUD presenta un proceso de pago cuya transacción se encuentra PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde para verificar si su pago fue confirmado de forma exitosa. Si desea mayor información sobre el estado actual de su operación puede comunicarse a nuestras líneas de atención al cliente o enviar un correo electrónico a hola@olu.com y preguntar por el estado de la transacción.";
                        $wpdb->insert('wtw_add_money', array(
                            'user_id' => $userID,
                            'txn_id' => $result->requestId,
                            'moneyPlan' => $planID,
                            'moneyValue' => str_replace("00000", "00", $priceValue),
                            'moneyAdded' => $priceGet,
                            'created_date' => $current,
                            'payment_status' => 2,
                            'ref_num' => $generateMyRefNumber
                        ));
                        $json = array("success" => 0, "result" => "", "error" => $message);

                        break;
                    } elseif ($result->status->status == "REJECTED") {
                        foreach ($result->payment as $key => $value) {
                            if ($value->reference == $generateMyRefNumber) {
                                $message = "El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información. Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago. Muchas gracias";
                            }
                        }
                        $wpdb->insert('wtw_add_money', array(
                            'user_id' => $userID,
                            'txn_id' => $result->requestId,
                            'moneyPlan' => $planID,
                            'moneyValue' => str_replace("00000", "00", $priceValue),
                            'moneyAdded' => $priceGet,
                            'created_date' => $current,
                            'payment_status' => 0,
                            'ref_num' => $generateMyRefNumber
                        ));
                        $json = array("success" => 0, "result" => "", "error" => $message);

                        break;
                    }
                } elseif (!empty($result1->request->payer) && $result1->subscription->instrument[0]->value == $token && $result1->status->status == "REJECTED") {
                    $json = array("success" => 0, "result" => "", "error" => "El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información. Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago. Muchas gracias");

                    break;
                } elseif (!empty($result1->request->payer) && $result1->subscription->instrument[0]->value == $token && $result1->status->status == "PENDING") {
                    $json = array("success" => 0, "result" => "", "error" => "“En este momento su SOLICITUD presenta un proceso de pago cuya transacción se encuentra PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde para verificar si su pago fue confirmado de forma exitosa. Si desea mayor información sobre el estado actual de su operación puede comunicarse a nuestras líneas de atención al cliente o enviar un correo electrónico a hola@olu.co y preguntar por el estado de la transacción.");

                    break;
                }
            }
        } else {
            $json = array("success" => 0, "result" => "", "error" => "Su transacción anterior está en progreso.");
        }
       
         /* else {
            update_user_meta($userID, "requestId", "");
            $json = array("success" => 0, "result" => "", "error" => "Token inválido Inténtalo de nuevo");
        } */
    }
}
echo json_encode($json);
