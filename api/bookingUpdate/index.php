<?php

date_default_timezone_set("America/Bogota");
include("../../wp-config.php");
$userID = $_GET['userID'];
$bookingID = $_GET['bookingID'];
$priceGroup = $_GET['bookingType'];
$address = $_GET['address'];
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $getBookingData = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
        if(empty($getBookingData)) {
            $json = array("success" => 0, "result" => 0, "error" => "Booking Inválido");
        } else {
            if($getBookingData[0]->booking_from == $userID) {
                if ($priceGroup == 1) {
                    $section = "single";
                    $testName = "single_price";
                } elseif ($priceGroup == 2) {
                    $section = "business";
                    $testName = "group_price";
                } elseif ($priceGroup == 4) {
                    $section = "business3";
                    $testName = "group_price3";
                } elseif ($priceGroup == 5) {
                    $section = "business4";
                    $testName = "group_price4";
                } else {
                    $section = "Company";
                    $testName = "company_price";
                }
                $wpdb->query("UPDATE `wtw_booking` SET `booking_address` = '".$address. "' , `booking_latitude` = '".$latitude. "' , `booking_longitude`  = '".$longitude. "' , `booking_for` =  '". $section ."' WHERE `id` = $bookingID");
                $json = array("success" => 1, "result" => 1, "error" => "No Error Found");
            } else {
                $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
            }
        }
    }
}
echo json_encode($json);
