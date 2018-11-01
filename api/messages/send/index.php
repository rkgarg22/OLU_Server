<?php 

include("../../../wp-config.php");
global $wpdb;
$data_body = json_decode(file_get_contents("php://input"), true);
/* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($data_body);
fwrite($myfile, $txt);
fclose($myfile); */
//Defining varables
date_default_timezone_set("America/Bogota");
$userID = $data_body['userID'];
$userIDTo = $data_body['userIDTo'];
$message = $data_body['message'];
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $messageCheck = messageCheck($userID , $userIDTo);
        if(empty($messageCheck)) { 
            $wpdb->insert('wtw_conversation', array(
                'message_from' => $userID,
                'message_to' => $userIDTo,
                'created_date' => date("Y-m-d H:i:s"),
                'last_updated' => date("Y-m-d H:i:s")
            ));
            $lastid = $wpdb->insert_id;
            $wpdb->insert('wtw_message', array(
                'conv_id' => $lastid,
                'message_text' => $message,
                'message_from' => $userID,
                'message_to' => $userIDTo,
                'created_at' => date("Y-m-d H:i:s")
            ));
        } else {
            $convID = $messageCheck[0]->id;
            $currentTime = date("Y-m-d H:i:s");
            $wpdb->query("UPDATE `wtw_conversation` SET `last_updated` = '$currentTime' WHERE `id` = $convID");
            $wpdb->insert('wtw_message', array(
                'conv_id' => $convID,
                'message_text' => $message,
                'message_from' => $userID,
                'message_to' => $userIDTo,
                'created_at' => date("Y-m-d H:i:s")
            ));
        }
        $curent = strtotime(date("Y-m-d H:i:s"));
        $target = get_user_meta($userIDTo , "firebaseTokenId" , true);
        $firstName = get_user_meta($userID , "first_name" , true);
        $lastName = get_user_meta($userID , "last_name" , true);
        $title = "OLU App";
        $message1 = $firstName. " Te ha enviado un mensaje nuevo";
        sendMessageChat($target, $title, $message1 , $conversationID , $userID , $firstName , $message, $lastName,$userIDTo , strtotime($currentTime));
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
?>