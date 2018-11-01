<?php 
include("../../../wp-config.php");
global $wpdb;
$data_body = json_decode(file_get_contents("php://input"), true);
$oldPassword = $data_body["oldPassword"];
$newPassword = $data_body["newPassword"];
$userID  = $data_body["userID"];
if($userID == "" || $oldPassword == "" || $newPassword =="") {
	$json = array("success" => 0, "result" => 0, "error" =>  "Todos los campos son obligatorios");
} else {
	$user = get_user_by( 'ID', $userID );
	if(empty($user)) {
		$json = array("success" => 0, "result" => 0, "error" =>  "Usuario Inválido");
	}
	else
	{
	if ( $user && wp_check_password( $oldPassword, $user->data->user_pass, $userID) ){
		wp_set_password( $newPassword, $userID );
	$json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
	}
	else{
	   $json = array("success" => 0, "result" => 0, "error" =>  "La contraseña antigua no coincide");
	}
	}
}
echo json_encode($json);