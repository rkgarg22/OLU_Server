<?php

include("../../../wp-config.php");
global $wpdb;
//Collecting Data
$userID = $_GET['userID'];
$agendaID = $_GET['agendaID'];
//Collecting Data

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $getData = $wpdb->get_results("SELECT * FROM `wtw_agenda` WHERE `id` = $agendaID");
        if(empty($getData)) {
            $json = array("success" => 0, "result" => 0, "error" => "Agenda Inválido");
        } else {
           if($getData[0]->user_id == $userID)  {
                $wpdb->get_results("DELETE FROM `wtw_agenda` WHERE `id` = $agendaID");
                $json = array("success" => 1, "result" => 1, "error" => "No Error Found");
           } else {
                $json = array("success" => 0, "result" => 0, "error" => "Agenda Not Belongs to this user");
           }
        }
    }
}
echo json_encode($json);