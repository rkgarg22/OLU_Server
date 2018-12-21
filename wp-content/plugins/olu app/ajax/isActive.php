<?php 
include("../../../../wp-config.php");
global $wpdb;
$checked = $_POST['checked'];
$userID = $_POST['userID'];
update_user_meta($userID , "isActive" , $checked);
?>