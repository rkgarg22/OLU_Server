<?php 
include("../../wp-config.php");
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
                if ($state == 1) {
                    // echo $dataMy[0]->booking_from;
                    $myWallet = getUserWallet($dataMy[0]->booking_from);
                    (int)$getThisBooking = getBookingPrice($bookingID);
                    $getPaymerDetails = getPaymerDetails($dataMy[0]->booking_from);
                    if ($myWallet < (int)$getThisBooking) {
                        $price = (int) $getThisBooking - $myWallet;
                        $token = getUserToken($dataMy[0]->booking_from);
                        $collectAPI = collectAPI($dataMy[0]->booking_from, $price, $token, $getPaymerDetails);
                    }
                    $wpdb->insert('wtw_booking_price', array(
                        'booking_id' => $bookingID,
                        'booking_price' => $getThisBooking,
                        'booking_paid' => 0
                    ));
                    update_user_meta($dataMy[0]->user_id , "isOnline" , 1 );
                    $wpdb->query("UPDATE `wtw_booking` SET `isPaid` = 1 WHERE `id` = $bookingID");
                        $message = " Se finaliza actividad";
                } else {

                    update_user_meta($dataMy[0]->user_id, "isOnline", 0);
                        $message = "Se inicia actividad";
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