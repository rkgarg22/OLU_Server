<?php 
include("../../../wp-config.php");
global $wpdb;
//Defining varables
$userID = $_GET['userID'];
$language = $_GET['language'];

if ($userID == "" || $language == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => null, "error" => "Usuario Inválido");
    } else {
        //collecting User Information
        $first_name = get_user_meta($userID, "first_name", true);
        $last_name = get_user_meta($userID, "last_name", true);
        $profileImageURL = get_user_meta($userID, "userImageUrl", true);

        //collectingBookingData
        $cateGoryName = array();
        if ($user->roles[0] == "contributor") {
            $getMyData = $wpdb->get_results("SELECT DISTINCT `category_id` FROM `wtw_booking` WHERE `userz_id` = $userID ORDER BY `id` DESC LIMIT 3");
        } else {
            $getMyData = $wpdb->get_results("SELECT DISTINCT `category_id` FROM `wtw_booking` WHERE `booking_from` = $userID ORDER BY `id` DESC LIMIT 3");
        }
        foreach ($getMyData as $key => $value) {

            $terMyTerm = get_term($value->category_id, "category");
            $termName = apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
            $cateGoryName[] = array("categoryID" => (int)$value->category_id, "name" => $termName);
        }
        $bookingArr = array();
        $getMyDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID ORDER BY `id` DESC ");
        foreach ($getMyDataBooking as $key => $value) {
            $terMyTerm = get_term($value->category_id, "category");
                // echo apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
            $firstNameC = get_user_meta($userID, "first_name", true);
            $lastNameC = get_user_meta($userID, "last_name", true);
            $userImageUrl = get_user_meta($userID, "userImageUrl", true);
            $phone = get_user_meta($userID, "phone", true);
            $price = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $value->user_id AND `meta_value` = $value->booking_price");
            if ($price[0]->meta_key == "single") {
                $section = 1;
            } elseif ($price[0]->meta_key == "business") {
                $section = 2;
            } else {
                $section = 3;
            }
            $bookingArr[] = array("date" => $value->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $value->booking_start, "bookingEnd" => $value->booking_end, "bookingType" => $section, "status" => $value->status, "phone" => $phone, "userImageUrl" => $userImageUrl, "bookingLatitude" => $value->booking_latitude, "bookingLongitude" => $value->booking_longitude, "bookingAddress" => $value->booking_address);
        }
        //collectingBookingData

        //Wallet Calculation
        $initWaller = 0;
        $initBooking = 0;
        $getMyMoney = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `user_id` = $userID");
        $useMoney = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 1");
        foreach ($getMyMoney as $key => $value) {
            $initWaller = $initWaller + $value->moneyAdded;
        }
        foreach ($useMoney as $key => $value) {
            $getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->user_id AND `category_id` = $value->category_id");

            if ($value->booking_for == "single") {
                $metaKey = "single_price";
            } elseif ($value->booking_for == "business") {
                $metaKey = "group_price";
            } elseif ($value->booking_for == "business3") {
                $metaKey = "group_price3";
            } elseif ($value->booking_for == "business4") {
                $metaKey = "group_price4";
            } else {
                $metaKey = "company_price";
            }
            $getBookingDetails[0]->$metaKey;
            $initBooking = $initBooking + $getBookingDetails[0]->$metaKey;
        }
        $finWallet = $initWaller - $initBooking;
        //Wallet Calculation
        $dataCollect = array("userID" => $userID, "firstName" => $first_name, "lastName" => $last_name, "image" => $profileImageURL, "categories" => $cateGoryName, "wallet" => getUserWallet($userID), "bookingHistory" => $bookingArr);
        $json = array("success" => 1, "result" => $dataCollect, "error" => "Datos no encontrados");
    }
}
echo json_encode($json);