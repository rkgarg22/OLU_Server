<?php 
include("../../../../wp-config.php");
global $wpdb;
$promoCode = $_POST['promoCode'];
$wpdb->query("UPDATE `wtw_promocode` SET `status` = 0 WHERE `id` = $promoCode");
echo "Success";
?>