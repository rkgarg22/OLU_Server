<?php

include("../../../wp-config.php");
global $wpdb;
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Collecting Data
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $data_body['userID'];
$agenda_text = $data_body['agenda_text'];
$agenda_date = $data_body['agenda_date'];
$agenda_start_time = $data_body['agenda_start_time'];
$agenda_end_time = $data_body['agenda_end_time'];
$agenda_type = $data_body['agenda_type'];
//Collecting Data

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        if (strtotime($agenda_start_time) > strtotime($agenda_end_time)) {
            $agenda_end_date = date("Y-m-d", strtotime('+1 day', strtotime($agenda_date)));
        } else {
            $agenda_end_date = $agenda_date;
        }

        $StartDateSet = $agenda_date . " " . $agenda_start_time;
        $endDateSet = $agenda_end_date . " " . $agenda_end_time;

        $strToTimeCheck = strtotime($StartDateSet);
        $getBookingCheck = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID AND `booking_date` = '$agenda_date' AND `status` = 3");
        $stat = "True";
        foreach ($getBookingCheck as $getBookingCheckkey => $getBookingCheckvalue) {

            $startDateCheck = $getBookingCheckvalue->booking_date . " " . $getBookingCheckvalue->booking_start;
            $endDateCheck = $getBookingCheckvalue->booking_date . " " . $getBookingCheckvalue->booking_end;
            if ((strtotime($StartDateSet) >= strtotime($startDateCheck)) && (strtotime($StartDateSet) <= strtotime($endDateCheck))) {
                $stat = "False";
            }
            if ((strtotime($endDateSet) >= strtotime($startDateCheck)) && (strtotime($endDateSet) <= strtotime($endDateCheck))) {
                $stat = "False";
            }

            if ((strtotime($startDateCheck) >= strtotime($StartDateSet)) && (strtotime($startDateCheck) <= strtotime($endDateSet))) {
                $stat = "False";
            }
            if ((strtotime($endDateCheck) >= strtotime($StartDateSet)) && (strtotime($endDateCheck) <= strtotime($endDateSet))) {
                $stat = "False";
            }
        }
        $getMyAgendaAvailable = getMyAgendaAvailable($userID , $agenda_date , $agenda_start_time , $agenda_end_time);
        $getMyBookingAvailable = getMyBookingAvailable($userID , $agenda_date , $agenda_start_time , $agenda_end_time);
        if($getMyAgendaAvailable == "True" && $stat != "False") {
            
            $wpdb->insert('wtw_agenda', array(
                'user_id' => $userID,
                'agenda_text' => $agenda_text,
                'agenda_date' => $agenda_date,
                'agenda_start_time' => $agenda_start_time,
                'agenda_end_date' => $agenda_end_date,
                'agenda_end_time' => $agenda_end_time,
                'agenda_type' => $agenda_type,
                'status' => 0
            ));
            $json = array("success" => 1, "result" => 1, "error" => "No Error Found");
        } else {
            $json = array("success" => 0, "result" => 0, "error" => "Por favor revisa tus eventos, la hora seleccionada ya está agendada.");
        }
    }
}
echo json_encode($json);