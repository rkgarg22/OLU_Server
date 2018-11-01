<?php
include("../../wp-config.php");
global $wpdb;
$userID = $_GET['userID'];
$promoCode = $_GET['promoCode'];
date_default_timezone_set("America/Bogota");
$currentDate = date("Y-m-d");

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$promoCode'");
        if(empty($getPromoCode)){
            $json = array("success" => 0, "result" => 0, "error" => "Promo Code Inválido");
        } else {
            $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 1 AND `promocode`  = '$promoCode'  OR `booking_from` = $userID AND  `isPaid` = 1 AND `promocode`  = '$promoCode' ORDER BY `booking_date` DESC ");
            if(empty($getUserDataBooking)) {
                $couponStart = $getPromoCode[0]->start_data;
                $couponEnd = $getPromoCode[0]->end_date;
                if ($currentDate >= date("Y-m-d", strtotime($couponStart))) {
                    update_user_meta($userID, "promoCode", $promoCode);
                    $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
                } else {
                    $json = array("success" => 0, "result" => 0, "error" => "Promo Code Inválido");
                }
            } else {
                $json = array("success" => 0, "result" => 0, "error" => "Promo Code Ya usado");
            }
            
        }
    }
}
echo json_encode($json);