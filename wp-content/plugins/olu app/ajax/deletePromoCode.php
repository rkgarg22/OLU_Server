<?php 
include("../../../../wp-config.php");
global $wpdb;
$promoCode = $_POST['promoCode'];
$wpdb->query("DELETE FROM `wtw_promocode` WHERE `id` = $promoCode");
echo "Success";
?>