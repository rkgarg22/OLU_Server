<?php

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ActiveUsers.csv');

$fp = fopen('php://output', 'wb');
include("../../wp-config.php");
date_default_timezone_set("America/Bogota");

$args = array(
    'role' => 'contributor'
);
$users = get_users($args);
$arrayPrep = array();
$arrayPrep[] =  array("firstName" => "Nombre de pila" , "lastName" => "Apellido" , "email" => "Dirección de correo electrónico" , "joiningDate" => "Dia de ingreso" , "description" => "Descripción" , "gender" => "Género" , "phone" => "Teléfono" , "dob" => "Fecha de nacimiento" , "age" => "Años");
foreach ($users as $key => $value) {
    $firstName = get_user_meta($value->ID , "first_name" , true);
    $lastName = get_user_meta($value->ID , "last_name" , true);
    $description = get_user_meta($value->ID , "description" , true);
    $gender = get_user_meta($value->ID , "gender" , true);
    $phone = get_user_meta($value->ID , "phone" , true);
    $dob = get_user_meta($value->ID , "dob" , true);
    $age = get_user_meta($value->ID , "age" , true);
    $isActive = get_user_meta($value->ID , "isActive" , true);
    if($isActive == "") {
        $isActive = 1;
    }
    if($isActive == 1) {
        $arrayPrep[] = array("firstName" => $firstName, "lastName" => $lastName, "email" => $value->data->user_login, "joiningDate" => $value->data->user_registered, "description" => $description, "gender" => $gender, "phone" => $phone, "dob" => $dob, "age" => $age);

    }
}
foreach ($arrayPrep as $key => $value) {

    fputcsv($fp, $value);
}

fclose($fp);
?>