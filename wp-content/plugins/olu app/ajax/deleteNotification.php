<?php 
include('../../../../wp-config.php');
global $wpdb;
$finalVar = $_POST['finalVar'];

$wpdb->query("DELETE  FROM `im_notification` WHERE `id` = '".$finalVar."'");