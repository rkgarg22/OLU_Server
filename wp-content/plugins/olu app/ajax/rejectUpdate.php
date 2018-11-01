<?php
include("../../../../wp-config.php");
global $wpdb;

$is = $_POST['updateID'];
$data = $wpdb->get_results("SELECT * FROM `wtw_user_update_log` WHERE `id` = $is");
$wpdb->query("UPDATE `wtw_user_update_log` SET `status` = 2 WHERE `id` = $is");

//Notification 
$target = get_user_meta($data[0]->user_id , "firebaseTokenId" , true);
$target = "OLU Fitness App";
$message = "Su solicitud para actualizar el perfil ha sido rechazada por el administrador. Vuelva a intentarlo más tarde.";
sendMessage($target, $title, $message);
?>