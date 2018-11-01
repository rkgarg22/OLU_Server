<?php

include("../../../wp-config.php");


$userID = $_GET['userID'];
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido Invalid");
    } else {
        update_user_meta($userID , "latitude" , $latitude);
        update_user_meta($userID , "longitude" , $longitude);
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);