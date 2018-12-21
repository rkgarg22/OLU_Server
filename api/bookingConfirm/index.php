<?php 

include("../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables
date_default_timezone_set("America/Bogota");
$curent = date("Y-m-d H:i:s");
$userID = $_GET['userID'];
$bookingID = $_GET['bookingID'];
$status = $_GET['state'];
$isPaid = $_GET['isPaymentRequire'];
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
   
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {

        $getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
        if ($getBookingDetails[0]->booking_for == "single") {
            $section = 1;
        } elseif ($getBookingDetails[0]->booking_for == "business") {
            $section = 2;
        } elseif ($getBookingDetails[0]->booking_for == "business3") {
            $section = 4;
        } elseif ($getBookingDetails[0]->booking_for == "business4") {
            $section = 5;
        } else {
            $section = 3;
        }

        $categoryData = get_term_by('id', $getBookingDetails[0]->category_id, 'category');
        $firstName = get_user_meta($getBookingDetails[0]->user_id, "first_name", true);
        $lastName = get_user_meta($getBookingDetails[0]->user_id, "last_name", true);
        $phone = get_user_meta($getBookingDetails[0]->user_id, "phone", true);

        $userImageUrl = get_user_meta($getBookingDetails[0]->user_id, "userImageUrl", true);
        $firebaseTokenId = get_user_meta($getBookingDetails[0]->user_id, "firebaseTokenId", true);

        $getCurrentTime = date("Y-m-d");
        $start = date_create($getBookingDetails[0]->booking_created);
        $end = date_create();
        $diff = date_diff($start, $end);
        $diff->i;
        $roles = $user->roles[0];
        if ($roles == "subscriber") {
            if($status == 2) {
                $firstNameU = get_user_meta($getBookingDetails[0]->booking_from, "first_name", true);
                $lastNameU = get_user_meta($getBookingDetails[0]->booking_from, "last_name", true);
                $phoneU = get_user_meta($getBookingDetails[0]->booking_from, "phone", true);
                $userImageUrlU = get_user_meta($getBookingDetails[0]->booking_from, "userImageUrl", true);
                $title = "OLU";
                $messageUser2 = $firstNameU . " ha cancelado la actividad";
                sendMessageData($firebaseTokenId, $title, $messageUser2, $getBookingDetails[0]->id, $firstNameU, $lastlastNameUName, $getBookingDetails[0]->category_id, $categoryData->name, $getBookingDetails[0]->booking_date, $phoneU, $getBookingDetails[0]->booking_start, $getBookingDetails[0]->booking_end, 7, $getBookingDetails[0]->booking_address, $getBookingDetails[0]->booking_latitude, $getBookingDetails[0]->booking_longitude, $userImageUrlU, $section, $getBookingDetails[0]->booking_created, $curent);
                $wpdb->query("UPDATE `wtw_booking` SET `status` = 7 , `booking_action_time` = '$curent' WHERE `id` = $bookingID");
                $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
                if($isPaid == 1) {
					 $token = getUserToken($getBookingDetails[0]->booking_from);

                        if (strpos($token, "False") !== false) {
                            if ($token == "False") {
                                $mes = "tarjeta expirada";
                            } else {
                                $mes = str_replace("False", '', $token);
                            }
                            $json = array("success" => 0, "result" => 0, "error" => $mes);
                            echo json_encode($json);
                            die();
                        } else {
							
                    $myWallet = getUserWallet($userID);
                    $getThisBooking = getBookingPrice($getBookingDetails[0]->id) + 2;
                    $getPaymerDetails = getPaymerDetails($userID);
                    if ($myWallet < $getThisBooking) {
                        $price = $getThisBooking - $myWallet;
                        $collectAPI = collectAPI($userID, $price, $token, $getPaymerDetails);
						if(strpos($collectAPI, "False") !== false) {
                                    $json = array("success" => 0, "result" => 0, "error" => str_replace("False", '', $collectAPI));
                                    echo json_encode($json);
                                    die();
                                }
                    } else {
                        $price = $getThisBooking;
                    }

                    $wpdb->query("UPDATE `wtw_add_money` SET `bookingID` = $bookingID WHERE `txn_id` = '" . $collectAPI . "'");
                    $wpdb->insert('wtw_booking_price', array(
                        'booking_id' => $getBookingDetails[0]->id,
                        'booking_price' => $getThisBooking,
                        'booking_paid' => 0
                    ));
                    $idBooking = $getBookingDetails[0]->id;
                    $wpdb->query("UPDATE `wtw_booking` SET `isPaid` = 1 WHERE `id` = $idBooking");
						}
                }
            } else {
                $json = array("success" => 0, "result" => 0, "error" => "Estado inválido");
            }
        } else {
            if ($diff->i <= 15 && $diff->h == 0) {
                if ($status == 2 || $status == 3) {
                //user1

                    $categoryData = get_term_by('id', $getBookingDetails[0]->category_id, 'category');
                    $firstName = get_user_meta($getBookingDetails[0]->user_id, "first_name", true);
                    $lastName = get_user_meta($getBookingDetails[0]->user_id, "last_name", true);

                    $userImageUrl = get_user_meta($getBookingDetails[0]->user_id, "userImageUrl", true);
                    $firebaseTokenId = get_user_meta($getBookingDetails[0]->booking_from, "firebaseTokenId", true);
                    $title = "OLU";
                    if ($status == 2) {
                        $message = "¡Lo sentimos! " . $firstName . " no puede realizar la actividad…";
                    } else {
                        $message = "Tu OLU " . $firstName . " ha confirmado la reserva";
                    }
                    if($getBookingDetails[0]->status  != 6) {
                        sendMessageData($firebaseTokenId, $title, $message, $getBookingDetails[0]->id, $firstName, $lastName, $getBookingDetails[0]->category_id, $categoryData->name, $getBookingDetails[0]->booking_date, $phone, $getBookingDetails[0]->booking_start, $getBookingDetails[0]->booking_end, $status, $getBookingDetails[0]->booking_address, $getBookingDetails[0]->booking_latitude, $getBookingDetails[0]->booking_longitude, $userImageUrl, $section, $getBookingDetails[0]->booking_created, $curent);
                    }
                //user1
                    $wpdb->query("UPDATE `wtw_booking` SET `status` = $status , `booking_action_time` = '$curent' WHERE `id` = $bookingID");
                    $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
                } else {
                    $json = array("success" => 0, "result" => 0, "error" => "Invalid Status");
                }
            } else {
                if($getBookingDetails[0]->status == 3 && $status == 2) {
                    $end = date_create($getBookingDetails[0]->booking_date." ".$getBookingDetails[0]->booking_start);
                    $start = date_create();
                    $diff = date_diff($start, $end);
                    // print_r($diff);
                    if ($diff->i >= 60 || $diff->h >= 1) {
                        $categoryData = get_term_by('id', $getBookingDetails[0]->category_id, 'category');
                        $firstNameUser2 = get_user_meta($getBookingDetails[0]->user_id, "first_name", true);
                        $lastName = get_user_meta($getBookingDetails[0]->user_id, "last_name", true);
                        $firebaseTokenIdUser2 = get_user_meta($getBookingDetails[0]->booking_from, "firebaseTokenId", true);
                        $userImageUrl = get_user_meta($getBookingDetails[0]->user_id, "userImageUrl", true);
                        $title = "OLU";
                        $messageUser2 = "Su reserva ha sido cancelada por " . $firstNameUser2 . "";
                    //user2

                        sendMessageData($firebaseTokenIdUser2, $title, $messageUser2, $getBookingDetails[0]->id, $firstNameUser2, $lastName, $getBookingDetails[0]->category_id, $categoryData->name, $getBookingDetails[0]->booking_date, $phone, $getBookingDetails[0]->booking_start, $getBookingDetails[0]->booking_end, 5, $getBookingDetails[0]->booking_address, $getBookingDetails[0]->booking_latitude, $getBookingDetails[0]->booking_longitude, $userImageUrl, $section, $getBookingDetails[0]->booking_created, $curent);
                        $wpdb->query("UPDATE `wtw_booking` SET `status` = 5 , `booking_action_time` = '$curent' WHERE `id` = $bookingID");
                        $json = array("success" => 1, "result" => 1, "error" => "Tu sesión ha terminado");
                    } else {
                        $json = array("success" => 0, "result" => 0, "error" => "No puedes cancelar la reserva ahora");
                    }
                } else {
                    //user1
                    $firstName = get_user_meta($getBookingDetails[0]->booking_from, "first_name", true);
                    $lastName = get_user_meta($getBookingDetails[0]->user_id, "last_name", true);
                    $firebaseTokenId = get_user_meta($getBookingDetails[0]->user_id, "firebaseTokenId", true);
                    $userImageUrl = get_user_meta($getBookingDetails[0]->booking_from, "userImageUrl", true);
                    $title = "OLU";
                    $message = "Su reserva de " . $firstName . " ha expirado, intente de nuevo";
                    sendMessageData($firebaseTokenId, $title, $message, $getBookingDetails[0]->id, $firstName, $lastName, $getBookingDetails[0]->category_id, $categoryData->name, $getBookingDetails[0]->booking_date, $phone, $getBookingDetails[0]->booking_start, $getBookingDetails[0]->booking_end, 2, $getBookingDetails[0]->booking_address, $getBookingDetails[0]->booking_latitude, $getBookingDetails[0]->booking_longitude, $userImageUrl, $section, $getBookingDetails[0]->booking_created, $curent);
                    //user1
                    //user2
                    $firstNameUser2 = get_user_meta($getBookingDetails[0]->user_id, "first_name", true);
                    $lastName2 = get_user_meta($getBookingDetails[0]->user_id, "last_name", true);
                    $firebaseTokenIdUser2 = get_user_meta($getBookingDetails[0]->booking_from, "firebaseTokenId", true);
                    $userImageUrl = get_user_meta($getBookingDetails[0]->user_id, "userImageUrl", true);
                    $title = "OLU";
                    $messageUser2 = "Su reserva de " . $firstNameUser2 . " ha expirado, intente de nuevo";
                    //user2
                    sendMessageData($firebaseTokenIdUser2, $title, $messageUser2, $getBookingDetails[0]->id, $firstNameUser2, $lastName2, $getBookingDetails[0]->category_id, $categoryData->name, $getBookingDetails[0]->booking_date, $phone, $getBookingDetails[0]->booking_start, $getBookingDetails[0]->booking_end, 2, $getBookingDetails[0]->booking_address, $getBookingDetails[0]->booking_latitude, $getBookingDetails[0]->booking_longitude, $userImageUrl, $section, $getBookingDetails[0]->booking_created, $curent);
                    $wpdb->query("UPDATE `wtw_booking` SET `status` = 2 , `booking_action_time` = '$curent' WHERE `id` = $bookingID");
                    $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
                }
            
            }
        }
        
    }
}
echo json_encode($json);
?>