<?php 

include("../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables
$userID = $_GET['userID'];
$language = $_GET['language'];
$Users = new Users();
if ($userID == "" || $language == "") {
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
	//checking User
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido");
    } else {
            $getMyMOverType = $Users->getCategoryListing($language);
            $json = array("success" => 1, "result" => $getMyMOverType, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
