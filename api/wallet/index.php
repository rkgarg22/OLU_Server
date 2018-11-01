<?php 
include("../../wp-config.php");
$userID = $_GET['userID'];
if ($userID == "") {
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido");
    } else {
        $data= getUserWallet($userID);

        $json = array("success" => 1, "result" => $data, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
?>