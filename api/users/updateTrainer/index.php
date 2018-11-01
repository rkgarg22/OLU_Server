<?php 
include("../../../wp-config.php");

global $wpdb;
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $data_body["userID"];
$city = $data_body["city"];
$categoryID = $data_body["categoryID"];
$description = $data_body["description"];
$workExperiance = $data_body["workExperiance"];
$certifications = $data_body["certifications"];
$studies = $data_body["studies"];
$singlePrice = $data_body["singlePrice"];
$groupPrice = $data_body["groupPrice"];
$companyPrice = $data_body["companyPrice"];
$hearAboutUs = $data_body["hearAboutUs"];