<?php 

include("../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables

date_default_timezone_set("America/Bogota");


$userID = $_GET['userID'];
$agenda_date = date("Y-m-d");
$agenda_start_time = date("H:i:s");
$agenda_end_time = date("H:i:s");

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $isOnline = get_user_meta($userID , "isOnline" , true);
        if($isOnline == 1) {
            $getMyAgendaAvailable = getMyAgendaAvailable($userID, $agenda_date, $agenda_start_time, $agenda_end_time);
            $getMyBookingAvailable = getMyBookingAvailable($userID, $agenda_date, $agenda_start_time, $agenda_end_time);
            if ($getMyAgendaAvailable == "True" && $getMyBookingAvailable == "True") {
                $status = 1;
            } else {
                $status = 0;
            }
        } else {
            $status  = 0;
        }

        $json = array("success" => 1, "result" => $status, "error" => "No se encontró ningún error");
    }
}
echo json_encode($json);