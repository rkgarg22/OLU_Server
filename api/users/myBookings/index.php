<?php 
include("../../../wp-config.php");
global $wpdb;
//Defining varables
$userID = $_GET['userID'];
$status = $_GET['status'];
$order = $_GET['order'];
$offSet = $_GET['offSet'];
$language = $_GET['language'];

if ($userID == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => null, "error" => "Usuario Inválido");
    } else {
        $getAllData  = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = $status ORDER BY `booking_date` $order");
        $bookingArr = array();
        foreach ($getAllData as $getUserDataBookingkey => $getUserDataBookingvalue) {
            $terMyTerm = get_term($getUserDataBookingvalue->category_id, "category");
                // echo apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
            $firstNameC = get_user_meta($getUserDataBookingvalue->user_id, "first_name", true);
            $phone_number = get_user_meta($getUserDataBookingvalue->user_id, "phone_number", true);
            $lastNameC = get_user_meta($getUserDataBookingvalue->user_id, "last_name", true);
            $userImageUrl = get_user_meta($getUserDataBookingvalue->user_id, "userImageUrl", true);
            $price = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $getUserDataBookingvalue->user_id AND `meta_value` = $getUserDataBookingvalue->booking_price");
            if ($price[0]->meta_key == "single") {
                $section = 1;
            } elseif ($price[0]->meta_key == "business") {
                $section = 2;
            } else {
                $section = 3;
            }
            $bookingArr[] = array("userID" => $getUserDataBookingvalue->user_id , "date" => $getUserDataBookingvalue->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $getUserDataBookingvalue->booking_start, "bookingEnd" => $getUserDataBookingvalue->booking_end, "bookingType" => $getUserDataBookingvalue->booking_for , "phone" => $phone_number, "userImageUrl" => $userImageUrl, "bookingLatitude" => $getUserDataBookingvalue->booking_latitude, "bookingLongitude" => $getUserDataBookingvalue->booking_longitude, "bookingAddress" => $getUserDataBookingvalue->booking_address);
        }
        if ($offSet == 1) {
            $offSet = 0;
        } else {
            $offSet = ($offSet - 1) * 20;
        }

        $bookingArr = array_slice($bookingArr, $offSet, 20);
        if(empty($bookingArr)) {
            $json = array("success" => 0, "result" => null, "error" => "Datos no encontrados");
        } else {
            $bookingArr = str_replace("null", '""', json_encode($bookingArr));
            $json = array("success" => 1, "result" => json_decode($bookingArr), "error" => "No se ha encontrado ningún error");    
        }
    }
}
echo json_encode($json);