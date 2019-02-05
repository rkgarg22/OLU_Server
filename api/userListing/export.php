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
// DESCRIPCION / GENERO / TELEFONO / FECHA DE NACIMIENTO / ESTADO / ACTIVIDADES / PRECIO
$arrayPrep[] =  array("firstName" => "NOMBRE" , "lastName" => "APELLIDO" , "email" => "EMAIL" , "joiningDate" => "FECHA DE INGRESO" , "description" => "DESCRIPCION" , "gender" => "GENERO" , "phone" => "TELEFONO" , "dob" => "FECHA DE NACIMIENTO" , "state" => "ESTADO"  , 'activities' => 'ACTIVIDADES');
foreach ($users as $key => $value) {
    $firstName = get_user_meta($value->ID , "first_name" , true);
    $lastName = get_user_meta($value->ID , "last_name" , true);
    $description = get_user_meta($value->ID , "description" , true);
    $gender = get_user_meta($value->ID , "gender" , true);
    $phone = get_user_meta($value->ID , "phone" , true);
    $dob = get_user_meta($value->ID , "dob" , true);
    $age = get_user_meta($value->ID , "age" , true);
    $isActive = get_user_meta($value->ID , "isActive" , true);
    $isApprove = get_user_meta($value->ID, "isApprove", true);
    $string = "";
    $getUserPricing  = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->ID");
    foreach ($getUserPricing as $getUserPricingkey => $getUserPricingvalue) {

        $terMyTerm = get_term($getUserPricingvalue->category_id, "category");
        $string .= $terMyTerm->name."\n";
        if($getUserPricingvalue->single_price != ""){
            $string .= "Precio Individual : $". $getUserPricingvalue->single_price."\n";
        }
        if($getUserPricingvalue->group_price != ""){
            $string .= "Precio grupo 2 : $". $getUserPricingvalue->group_price ."\n";
        }
        if($getUserPricingvalue->group_price3 != ""){
            $string .= "Precio grupo 3 : $". $getUserPricingvalue->group_price3 ."\n";
        }
        if($getUserPricingvalue->group_price4 != ""){
            $string .= "Precio grupo 4 : $". $getUserPricingvalue->group_price4 ."\n";
        }
        if($getUserPricingvalue->company_price != ""){
            $string .= "Precio Empresarial : $". $getUserPricingvalue->company_price ."\n\n";
        }
    }
    if($isActive == "") {
        $isActive = 1;
    }
    if ($isApprove == "yes") {
        $isActive = get_user_meta($value->ID, "isActive", true);
        
        if ($isActive == 0) {
            $myStatus = "INACTIVO";

    } else {
        $myStatus = 'APROBADO';
}
} else {
$myStatus = 'PENDIENTE';

}
        $arrayPrep[] = array("firstName" => $firstName, "lastName" => $lastName, "email" => $value->data->user_login, "joiningDate" => $value->data->user_registered, "description" => $description, "gender" => $gender, "phone" => $phone, "dob" => $dob, "state" => $myStatus , "activities" => $string);
}
foreach ($arrayPrep as $key => $value) {

    fputcsv($fp, $value);
}

fclose($fp);
?>