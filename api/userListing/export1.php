<?php

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ActiveUsers.csv');

$fp = fopen('php://output', 'wb');
include("../../wp-config.php");
date_default_timezone_set("America/Bogota");

$args = array(
    'role' => 'subscriber'
);
$users = get_users($args);
$arrayPrep = array();
// DESCRIPCION / GENERO / TELEFONO / FECHA DE NACIMIENTO / ESTADO / ACTIVIDADES / PRECIO
$arrayPrep[] = array("firstName" => "NOMBRE", "lastName" => "APELLIDO", "email" => "EMAIL", "phone" => "TELEFONO" , "gender" => "GENERO");
foreach ($users as $key => $value) {
    $firstName = get_user_meta($value->ID, "first_name", true);
    $lastName = get_user_meta($value->ID, "last_name", true);
    $phone = get_user_meta($value->ID, "phone", true);
    $gender = get_user_meta($value->ID, "gender", true);
    $arrayPrep[] = array("firstName" => $firstName, "lastName" => $lastName, "email" => $value->data->user_login, "phone" => $phone , "gender" => $gender);
}
foreach ($arrayPrep as $key => $value) {

    fputcsv($fp, $value);
}

fclose($fp);
?>