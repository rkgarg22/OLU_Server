<?php 
include("../../../../wp-config.php");
global $wpdb;
$id = $_POST['id'];
$discount = $_POST['discount'];
$name = $_POST['name'];
$wpdb->query("UPDATE `wtw_promocode` SET `name` = '$name' WHERE `id` = $id");
$wpdb->query("UPDATE `wtw_promocode` SET `discount` = $discount WHERE `id` = $id");
echo "Success";
?>