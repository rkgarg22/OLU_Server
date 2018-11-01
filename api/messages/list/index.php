<?php 
include("../../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables
date_default_timezone_set("America/Bogota");
$userID = $_GET['userID'];
$userIDTo = $_GET['userIDTo'];
if ($userID == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => null, "error" => "Usuario Inválido");
    } else {
        $messageCheck = messageCheck($userID, $userIDTo);
        if(empty($messageCheck)) {
            $json = array("success" => 0, "result" => null, "error" => "No se encontró ningún mensaje");
        } else {
            $convID = $messageCheck[0]->id;
            $getAllMessage = $wpdb->get_results("SELECT * FROM `wtw_message` WHERE `conv_id` = $convID ORDER BY `id` DESC");
            $messageArray = array();
            foreach ($getAllMessage as $key => $value) {
                $message_from = get_user_meta($value->message_from , "first_name" , true);
                $message_to = get_user_meta($value->message_to , "first_name" , true);
                $messageArray[] = array("messageID" => (int)$value->id , "messageFromID" => (int)$value->message_from , "messageToID" => (int)$value->message_to, "messageFrom" => (int)$message_from , "messageTo" => (int)$message_to , "message" => $value->message_text , "messageTime" => strtotime($value->created_at));
            }

            $json = array("success" => 1, "result" => $messageArray, "error" => "No se ha encontrado ningún error");
        }
    }
}
echo json_encode($json);