<?php 
include("../../../wp-config.php");
global $wpdb;
//Defining varables
$userID = $_GET['userID'];

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $myUserRole = $user->roles[0];
        $wpdb->query("UPDATE `wtw_usermeta` SET `meta_value`= '' WHERE `meta_key` = 'firebaseTokenId' AND `user_id` = $userID");
        update_user_meta($userID, "firebaseTokenId", "", true);
        update_user_meta($userID, "isOnline", 0, true);
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
?>