<?php 
include("../../../wp-config.php");

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

//collecting Data
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $data_body['userID'];
$firstName = $data_body['firstName'];
$lastName = $data_body['lastName'];
$dob = $data_body['dob'];
$gender = $data_body['gender'];
$catID = $data_body['category']['id'];
$catID = $data_body['category']['singlePrice'];