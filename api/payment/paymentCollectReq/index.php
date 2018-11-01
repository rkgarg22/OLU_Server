<?php
include("../../../wp-config.php");
$userID = $_GET['userID'];
$requestIdNew = $_GET['requestId'];
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $requestId = get_user_meta($userID , "requestId" , true);
        $requestId = json_decode($requestId);
        $getType = gettype($requestId);
        if($getType == "integer") {
            $requestId = (array)$requestId;
            $requestId[] = $requestIdNew;
        } else {
            $requestId[] = $requestIdNew;
        }
        update_user_meta($userID , "requestId" , json_encode($requestId));
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
