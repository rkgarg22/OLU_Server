<?php

date_default_timezone_set("America/Bogota");
$current = date("Y-m-d H:i:s");
include("../../../wp-config.php");
$userID = $_GET['userID'];
$token = $_GET['token'];
$login = "fcec4c9fd9ea26079d9302b2424d38ea";
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
$tranKey = base64_encode(sha1($nonce . $seed . "92EukRSJ82Vr0TUt", true));
if ($userID == "") {
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido");
    } else {
        //Defining Price
            if($planID == 1) {
                $priceValue = "300000";
                $priceGet = 315;
            } elseif($planID == 2){
                $priceValue = "600000";
                $priceGet = 645;
            } else {
                $priceValue = "900000";
                $priceGet = 990;
            }
        //Defining Price


        $getUserToekn = get_user_meta($userID, "requestId", true);
        $requestId = json_decode($getUserToekn);
        $getType = gettype($requestId);
        if ($getType == "integer") {
            $requestId = (array)$requestId;
        } 
        foreach ($requestId as $key => $value) {
            $authentication = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" }}';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/session/" . $value);
            curl_setopt($ch, CURLOPT_POST, count(json_decode($authentication)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $authentication);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            $result = curl_exec($ch);
            $result = json_decode($result);
            curl_close($ch);
            if (!empty($result->request->payer) && $result->subscription->instrument[0]->value == $token) {
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
               $collectData = '{ "auth": {"login": "' . $login . '", "seed" : "' . $seed . '", "nonce" :"' . $nonceBase64 . '" ,  "tranKey" :"' . $tranKey . '" },  "instrument": { "token": { "token": "' . $token . '" } } , "payer" : ' . json_encode($result->request->payer) . ' , "payment": { "reference": "'. $generateMyRefNumber .'", "description": "Pago básico de prueba", "amount": { "currency": "COP", "total": "'. $priceValue .'" } }}';
                $ch = curl_init();
                $agents = array(
                    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                    'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
                    'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
                    'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'

                );
                curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);
                curl_setopt($ch, CURLOPT_URL, "https://test.placetopay.com/redirection/api/collect/");
                curl_setopt($ch, CURLOPT_POST, count(json_decode($collectData)));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $collectData);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                $result = curl_exec($ch);
                $result = json_decode($result);
                curl_close($ch);
                $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
                $txt = json_encode($result) . $collectData;
                fwrite($myfile, $txt);
                fclose($myfile);
                if($result->status->status == "APPROVED") {

                    $json = array("success" => 1, "result" => $result->requestId, "error" => "No se ha encontrado ningún error");
                    $wpdb->insert('wtw_add_money', array(
                        'user_id' => $userID,
                        'txn_id' => $result->requestId,
                        'moneyPlan' => $planID,
                        'moneyValue' => str_replace("00000" , "00" , $priceValue),
                        'moneyAdded' => $priceGet,
                        'created_date' => $current,
                        'ref_num' => $generateMyRefNumber
                    ));
                    break;
                } else {
                    foreach ($result->payment as $key => $value) {
                        if($value->reference == $generateMyRefNumber) {
                            $message = $value->status->message;
                        }
                    }
                    $json = array("success" => 0, "result" => "", "error" => $message);
                   
                    break;
                }
            }
        }
       
         /* else {
            update_user_meta($userID, "requestId", "");
            $json = array("success" => 0, "result" => "", "error" => "Token inválido Inténtalo de nuevo");
        } */
    }
}
echo json_encode($json);
