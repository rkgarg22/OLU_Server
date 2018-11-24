<?php
include("../../../../wp-config.php");
require_once "/usr/share/php/Mail.php";

global $wpdb;

$is = $_POST['updateID'];
$data = $wpdb->get_results("SELECT * FROM `wtw_user_update_log` WHERE `id` = $is");
$check = $data[0];

update_user_meta($check->user_id , "first_name" , $check->first_name);
update_user_meta($check->user_id , "last_name" , $check->last_name);
update_user_meta($check->user_id , "description" , $check->description);
update_user_meta($check->user_id , "gender" , $check->gender);
update_user_meta($check->user_id , "dob" , $check->dob);
update_user_meta($check->user_id , "age" , $check->age);
update_user_meta($check->user_id , "phone" , $check->phone);


//Deleting Old Prices 
$wpdb->query("DELETE FROM `wtw_user_pricing` WHERE `user_id` = $check->user_id");
            //Deleting Old Prices 
foreach (json_decode($check->categories) as $key => $value) {
    $wpdb->insert('wtw_user_pricing', array(
        'user_id' => $check->user_id,
        'category_id' => $value->CategryID,
        'single_price' => $value->singlePrice,
        'group_price' => $value->groupPrice2,
        'group_price3' => $value->groupPrice3,
        'group_price4' => $value->groupPrice4,
        'company_price' => $value->companyPrice
    ));
}
$wpdb->query("UPDATE `wtw_user_update_log` SET `status` = 1 WHERE `id` = $is");

//Notification 
$target = get_user_meta($data[0]->user_id, "firebaseTokenId", true);
$lastName = get_user_meta($data[0]->user_id, "last_name", true);
$user = get_userdata($data[0]->user_id);

$target = "OLU Fitness App";
$message = "Su solicitud para actualizar el perfil ha sido aceptada por el administrador.";
sendMessage($target, $title, $message);


?>