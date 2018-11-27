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
        $requestId = get_user_meta($userID, "requestId", true);
        $selectedCard = get_user_meta($userID, "selectedCard", true);
        $requestId = json_decode($requestId);
        $getType = gettype($requestId);
        if ($getType == "integer") {
            $requestId = (array)$requestId;
        } 
        if(in_array($requestIdNew, $requestId)) {
            $key = array_search($requestIdNew, $requestId);
            unset($requestId[$key]);
            $requestId = array_values($requestId);
            if(count($requestId) == 0){
                update_user_meta($userID, "requestId", "");
            } else {
                update_user_meta($userID, "requestId", json_encode($requestId));   
            }
            if($requestIdNew == $selectedCard) {
                update_user_meta($userID, "selectedCard", "");
            }
            $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
        } else {
            $json = array("success" => 0, "result" => 0, "error" => "Invalid Request ID");
        }
    }
}
echo json_encode($json);
