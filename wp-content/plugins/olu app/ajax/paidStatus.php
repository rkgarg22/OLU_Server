<?php 
include("../../../../wp-config.php");
global $wpdb;
$bookingID = $_POST['bookingID'];
$userID = $_POST['userID'];
$wpdb->query("UPDATE `wtw_booking_price` SET `booking_paid` = 1 WHERE `booking_id` = $bookingID");
$firebase = get_user_meta($userID , "firebaseTokenId" , true);
$title = "OLU Fitness APP";
$message = "El administrador ha aprobado su pago para la reserva";
sendMessage($firebase, $title, $message)
?>