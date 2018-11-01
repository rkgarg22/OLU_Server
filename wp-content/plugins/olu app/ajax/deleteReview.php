<?php 
include("../../../../wp-config.php");
global $wpdb;
$reviewID = $_POST['reviewID'];
$wpdb->query("DELETE FROM `wtw_booking_reviews` WHERE `id` = $reviewID");
echo "success";
?>