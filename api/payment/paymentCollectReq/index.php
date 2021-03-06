<?php
include("../../../wp-config.php");
require_once "/usr/share/php/Mail.php";
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
                
                //if cards is single and needs to approved

            $first_name = get_user_meta($userID, "first_name", true);
            $last_name = get_user_meta($userID, "last_name", true);
                                //SMTP SETUP
                        $from = "oluappinfo@gmail.com";
                        $host = "ssl://smtp.gmail.com";
                        $username = "oluappinfo@gmail.com";
                        $password = "sergiomauriciogmail18";
                        $port = "465";
                        $subject = "Información de OLU. App";
                        $to = $user->data->user_email;
                        $headers = array(
                            'From' => $from,
                            'To' => $to,
                            'Content-Type' => "text/html; charset=ISO-8859-1rn",
                            'Subject' => $subject
                        );
                        $smtp = Mail::factory(
                            'smtp',
                            array(
                                'host' => $host,
                                'auth' => true,
                                'port' => $port,
                                'username' => $username,
                                'password' => $password
                            )
                        );

                        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3> Bienvenido (a) " . $first_name . " " . $last_name . ",</h3> </td></tr><tr> <td> <h3> Gracias por inscribir tu medio de pago en PlaceToPay. Para tu información no se te hizo ningún cobro; los cobros solo se hacen una vez recibida la sesión. <h3> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Bienvenido a OLU!!</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
                        $mail = $smtp->send($to, $headers, $message2);
                    //if cards is single and needs to approved
				$json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
			} elseif($result1->status->status == "PENDNING") {
				$json = array("success" => 1, "result" => 1, "error" => "En este momento su SOLICITUD presenta un proceso de pago cuya transacción se encuentra PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde para verificar si su pago fue confirmado de forma exitosa. Si desea mayor información sobre el estado actual de su operación puede comunicarse a nuestras líneas de atención al cliente o enviar un correo electrónico a hola@olu.co y preguntar por el estado de la transacción.");
			} elseif($result1->status->status == "REJECTED") {
				$json = array("success" => 1, "result" => 1, "error" => "El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información. Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago. Muchas gracias");
			}
    }
}
echo json_encode($json);
