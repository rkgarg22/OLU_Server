<?php 
include("../../../wp-config.php");

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

//collecting Data
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $_GET['userID'];
$trainerID = $_GET['trainerUserID'];
$categoryID = $_GET['categoryID'];
$date = $_GET['date'];
$time = $_GET['time'];
$StartDateSet = $date . " " . $time;
$strToTimeCheck = strtotime($StartDateSet);


//Prepairing Data
if ($category == 8) {
    $endDateSet = date("Y-m-d H:i:s", strtotime('+90 minutes', strtotime($StartDateSet)));
} else {
    $endDateSet = date("Y-m-d H:i:s", strtotime('+1 hours', strtotime($StartDateSet)));
}
//Prepairing Data
if ($userID == "") {
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
	//checking User
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido Invalid");
    } else {
        //Getiing Booking Check
        $dateCheck = date("Y-m-d", strtotime($date));
        $getBookingCheck = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $trainerUserID AND `booking_date` = '$dateCheck'");
        $stat = "True";
        foreach ($getBookingCheck as $getBookingCheckkey => $getBookingCheckvalue) {
            $startDateCheck = $getBookingCheckvalue->booking_date . " " . $getBookingCheckvalue->booking_start;
            $endDateCheck = $getBookingCheckvalue->booking_date . " " . $getBookingCheckvalue->booking_end;
            if (($strToTimeCheck > strtotime($startDateCheck)) && ($strToTimeCheck < strtotime($endDateCheck))) {
                $stat = "False";
            }
            if ((strtotime($endDateSet) > strtotime($startDateCheck)) && (strtotime($endDateSet) < strtotime($endDateCheck))) {
                $stat = "False";
            }
        }
        //Getiing Booking Check

        $json = array("success" => 1, "result" => $stat, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);

