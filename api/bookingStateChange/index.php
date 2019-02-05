<?php 
include("../../wp-config.php");
require_once "/usr/share/php/Mail.php";
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables
date_default_timezone_set("America/Bogota");
$curent = date("Y-m-d H:i:s");
$userID = $_GET['userID'];
$bookingID = $_GET['bookingID'];
$state = $_GET['state'];// 1 and 4

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        // echo "SELECT * FROM `wtw_booking`  WHERE `user_id` = $userID AND `status` = 4 ORDER BY `id` DESC LIMIT 1";
        $dataCheck = $wpdb->get_results("SELECT * FROM `wtw_booking`  WHERE `user_id` = $userID AND `status` = 4 ORDER BY `id` DESC LIMIT 1");
        
        $dataStatus = $wpdb->get_results("SELECT * FROM `wtw_booking`  WHERE `id` = $bookingID");
       if($dataStatus[0]->booking_date == date("Y-m-d")) {

        if(empty($dataCheck) || $_GET['state'] == 1) {
            if($dataStatus[0]->status == 1) {
                $json = array("success" => 0, "result" => 0, "error" => "Reservas ya completadas");
            } else {
                $dataMy = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
                // print_r($dataMy);
                $firstName = get_user_meta($dataMy[0]->user_id, "first_name", true);
                $lastName = get_user_meta($dataMy[0]->user_id, "last_name", true);
                $userImageUrl = get_user_meta($dataMy[0]->user_id, "userImageUrl", true);
                $phone = get_user_meta($dataMy[0]->user_id, "phone", true);
                $firebaseTokenId = get_user_meta($dataMy[0]->booking_from, "firebaseTokenId", true);
                $title = "OLU";
                if ($state == 4) {
                    // echo $dataMy[0]->booking_from;
                    $myWallet = getUserWallet($dataMy[0]->booking_from);
                    $getThisBooking = getBookingPrice($bookingID) + 2;
                    $getThisBooking1 = getBookingPrice1($bookingID) + 2;
                    $getPaymerDetails = getPaymerDetails($dataMy[0]->booking_from);
                    $token = getUserToken($dataMy[0]->booking_from);
                    if ($myWallet < $getThisBooking) {
                        $paymentStatus = 1;
                    } else {
                        $paymentStatus = 0;
                    }
                        if (strpos($token, "False") !== false && $paymentStatus != 0) {
                            if ($token == "False") {
                                $mes = "tarjeta expirada";
                            } else {
                                $mes = str_replace("False", '', $token);
                            }
                            $json = array("success" => 0, "result" => 0, "error" => $mes);
                            echo json_encode($json);
                            die();
                        } else {
                            if ($myWallet < $getThisBooking) {
                                $price = $getThisBooking - $myWallet;
                                $collectAPI = collectAPI($dataMy[0]->booking_from, $price, $token, $getPaymerDetails);
                                if(strpos($collectAPI, "False") !== false) {
									$toData = get_userdata($dataMy[0]->booking_from );
								$to = $toData->data->user_login;
								 $from = "oluappinfo@gmail.com";
								$host = "ssl://smtp.gmail.com";
								$username = "oluappinfo@gmail.com";
								$password = "sergiomauriciogmail18";
								$port = "465";

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
                                $mes = "El método de pago de el usuario ha sido RECHAZADO. Si desea continuar con el proceso de OLU, él puede actualizar la tarjeta de la siguiente manera: - desde el perfil de usuario - - seleccionar pagos - métodos de pago - añadir método de pago.Muchas gracias Equipo OLU";
								if (strpos($token, "False") !== false) {}
								if(strpos($collectAPI, "FalseEl método") !== false) {
									$message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> </td></tr><tr> <td> <h3> El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información.  Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago por la aplicación así: <h3> <h3> - ir a mi perfil - seleccionar pagos - métodos de pago - añadir método de pago.  <h3> </td></tr><tr> <td>  </td></tr><tr> <td> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Muchas gracias<br> Equipo OLU!!</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
								} else {
									$message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> </td></tr><tr> <td> <h3> En este momento su SOLICITUD presenta un proceso de pago cuya transacción se encuentra PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde para verificar si su pago fue confirmado de forma exitosa. Si desea mayor información sobre el estado actual de su operación puede comunicarse a nuestras líneas de atención al cliente o enviar un correo electrónico a hola@olu.co y preguntar por el estado de la transacción.<h3> </td></tr><tr> <td>  </td></tr><tr> <td> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Muchas gracias<br> Equipo OLU!!</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
								}
								$mail = $smtp->send($to, $headers, $message2);
                                    $json = array("success" => 0, "result" => 0, "error" => $mes);
                                    echo json_encode($json);
                                    die();
                                }
                            }
                            $wpdb->query("UPDATE `wtw_add_money` SET `bookingID` = $bookingID WHERE `txn_id` = '" . $collectAPI . "'");
                            $wpdb->insert('wtw_booking_price', array(
                                'booking_id' => $bookingID,
                                'booking_price' => $getThisBooking1,
                                'booking_paid' => 0
                            ));
                            update_user_meta($dataMy[0]->user_id, "isOnline", 0);
                            $wpdb->query("UPDATE `wtw_booking` SET `isPaid` = 1 WHERE `id` = $bookingID");
                            $message = "Se inicia actividad";
                        }
                  
                } else {

                    update_user_meta($dataMy[0]->user_id, "isOnline", 1);
                        $message = " Se finaliza actividad";
                }
                if ($dataMy[0]->booking_for == "single") {
                    $section = 1;
                } elseif ($dataMy[0]->booking_for == "business") {
                    $section = 2;
                } elseif ($dataMy[0]->booking_for == "business3") {
                    $section =  4;
                } elseif ($dataMy[0]->booking_for == "business4") {
                    $section = 5;
                } else {
                    $section = 3;
                }
                sendMessageData($firebaseTokenId, $title, $message, $dataMy[0]->id, $firstName, $lastName, $dataMy[0]->category_id, $categoryData->name, $dataMy[0]->booking_date, $phone, $dataMy[0]->booking_start, $dataMy[0]->booking_end, $state, $dataMy[0]->booking_address, $dataMy[0]->booking_latitude, $dataMy[0]->booking_longitude, $userImageUrl , $section, $dataMy[0]->booking_created, $curent);
                $wpdb->query("UPDATE `wtw_booking` SET `status` = $state, `booking_action_time` = '$curent' WHERE `id` = $bookingID");
                $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
            }
        } else {
            $json = array("success" => 0, "result" => 0, "error" => "Ya has comenzado otra reserva");
        }

    } else {
            $json = array("success" => 0, "result" => 0, "error" => "Esta reserva no es para hoy.");
    }
    }
}
echo json_encode($json);
?>