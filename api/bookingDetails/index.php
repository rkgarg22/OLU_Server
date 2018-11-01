<?php 
include("../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables
date_default_timezone_set("America/Bogota");
$userID = $_GET['userID'];
$bookingID = $_GET['bookingID'];
$language = $_GET['lang'];
if ($userID == "") {
    $json = array("success" => 0, "result" => array(), "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => array(), "error" => "Usuario Inválido");
    } else {
        $admin_email = get_option('qtranslate_term_name');

        $getBookingDetails = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
        if($getBookingDetails[0]->booking_for == "single") {
            $metaKey = "single_price";
            $title = "Single";
        } elseif($getBookingDetails[0]->booking_for == "business") {
            $metaKey = "group_price";
            $title = "Group Price";
        } elseif ($getBookingDetails[0]->booking_for == "business3") {
            $metaKey = "group_price3";
            $title = "Group Price for 3";
        } elseif ($getBookingDetails[0]->booking_for == "business4") {
            $metaKey = "group_price4";
            $title = "Group Price for 4";
        } else {
            $metaKey = "company_price";
            $title = "Company";
        }
        $catID = $getBookingDetails[0]->category_id;
        $userIDD = $getBookingDetails[0]->user_id;

        $userImageUrl = get_user_meta($getBookingDetails[0]->user_id, "userImageUrl", true);
        $getPrice = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $userIDD AND `category_id` = $catID");
        $bookingPrice = $getBookingDetails[0]->booking_price;
        $term = get_term_by('id', $getBookingDetails[0]->category_id, 'category');
        $termName = apply_filters('translate_text', $term->name, $lang = $language, $flags = 0);//$admin_email[$term->name][$language];
        $bookingUSer = get_user_meta($getBookingDetails[0]->user_id , "first_name" , true);
        $profileImageURL = get_user_meta($getBookingDetails[0]->user_id, "userImageUrl", true);
        $getMetaKey = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $userIDD AND `meta_value` = $bookingPrice");
        $getBookingDetails1 = array("bookingID" => (int)$bookingID , "categoryID" => (int)$getBookingDetails[0]->category_id , "categoryName" => $termName , "bookingDate" => $getBookingDetails[0]->booking_date , "bookingTime" => $getBookingDetails[0]->booking_start, "bookingUserName" => $bookingUSer , "bookingUser" => $profileImageURL , "review" => 5 , "bookingFor" => $getPrice[0]->$metaKey  , "bookingPrice" => $title , "bookingLatitude" => $getBookingDetails[0]->booking_latitude , "bookingLongitude" => $getBookingDetails[0]->booking_longitude , "bookingAddress" => $getBookingDetails[0]->booking_address , "userImageUrl" => $userImageUrl);
        $getBookingDetails1 = str_replace("null", '""', json_encode($getBookingDetails1));

        $json = array("success" => 1, "result" => json_decode($getBookingDetails1), "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);