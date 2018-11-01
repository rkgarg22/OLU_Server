<?php 
include("../../wp-config.php");

global $wpdb;
date_default_timezone_set("America/Bogota");
$userID = $_GET['userID'];
$language = $_GET['language'];
$date = $_GET['date'];
$currentDate = date("Y-m-d" , strtotime($date));
if ($userID == "") {
    $json = array("success" => 0, "result" => array(), "error" => "Todos los campos son obligatorios");
} else {
    getMyExpiredBooking($userID);
    $getMyAgendaDetails= getMyAgendaDetails($userID , $date);
	//checking User
    $user = get_user_by('ID', $userID);
    $checkUser = get_user_by('ID', $checkUserID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => array(), "error" => "Usuario Inválido");
    } else {
        $admin_email = get_option('qtranslate_term_name');
        if ($user->roles[0] == "contributor") {
            // echo "SELECT * FROM `wtw_booking` WHERE  `user_id` = $userID AND  DATE(`booking_date`) = '" . $currentDate . "' AND `status` = 3 OR  `status` = 1 ORDER BY `booking_date` ASC";
            $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE  `user_id` = $userID AND  DATE(`booking_date`) = '" . $currentDate . "' AND `status` = 3 OR `user_id` = $userID AND  DATE(`booking_date`) = '" . $currentDate . "' AND `status` = 1 ORDER BY `booking_start` ASC");
           // $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID AND `status` = $status ORDER BY `booking_date` $order");
        } else {
            $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE  `booking_from` = $userID AND  DATE(`booking_date`) = '" . $currentDate . "' AND `status` = 3 OR `booking_from` = $userID AND  DATE(`booking_date`) = '" . $currentDate . "' AND`status` = 1 ORDER BY `booking_start` ASC");
            //$getAllData = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = $status ORDER BY `booking_date` $order");
        }
        // $getBookingAgenda = $wpdb->get_results("SELECT * FROM `wtw_agenda` WHERE `user_id` = $userID  AND DATE(`agenda_date`) = '" . $currentDate . "'");
        
        $bookingArr = array();
        foreach ($getUserDataBooking as $getUserDataBookingkey => $getUserDataBookingvalue) {
            $terMyTerm = get_term($getUserDataBookingvalue->category_id, "category");
            $firstNameC = get_user_meta($getUserDataBookingvalue->booking_from, "first_name", true);
            $userImageUrl = get_user_meta($getUserDataBookingvalue->booking_from, "userImageUrl", true);
            $phone = get_user_meta($getUserDataBookingvalue->booking_from, "phone", true);
            $lastNameC = get_user_meta($getUserDataBookingvalue->booking_from, "last_name", true);
            
            $bookingArr[] = array("userID" => (int)$getUserDataBookingvalue->booking_from, "bookingID" => (int)$getUserDataBookingvalue->id, "categoryID" => (int)$getUserDataBookingvalue->category_id, "bookingDate" => $getUserDataBookingvalue->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $getUserDataBookingvalue->booking_start, "bookingEnd" => $getUserDataBookingvalue->booking_end, "phone" => $phone, "userImageUrl" => $userImageUrl, "bookingLatitude" => $getUserDataBookingvalue->booking_latitude, "bookingLongitude" => $getUserDataBookingvalue->booking_longitude, "bookingAddress" => $getUserDataBookingvalue->booking_address , "status" => (int)$getUserDataBookingvalue->status, "isAgenda" => 0, "agendaID" => "", "agendaText" => "", "agendaType" => $getBookingAgendavalue->agenda_type);
        }
        
        /* foreach ($getBookingAgenda as $getBookingAgendakey => $getBookingAgendavalue) {
            
            $bookingArr[] = array("userID" => "", "bookingID" => "", "categoryID" => "", "bookingDate" => $getBookingAgendavalue->agenda_date, "category" => "Personal", "firstName" => "", "lastName" => "", "bookingStart" => $getBookingAgendavalue->agenda_start_time, "bookingEnd" => $getBookingAgendavalue->agenda_end_time, "phone" => $phone, "userImageUrl" => $userImageUrl, "bookingLatitude" => "", "bookingLongitude" => "", "bookingAddress" => "" , "status" => "" , "isAgenda" => 1 , "agendaID" => $getBookingAgendavalue->id , "agendaText" => $getBookingAgendavalue->agenda_text , "agendaType" => $getBookingAgendavalue->agenda_type);
        } */
        foreach ($getMyAgendaDetails as $getMyAgendaDetailskey => $getMyAgendaDetailsvalue) {
            
            $bookingArr[] = array("userID" => "", "bookingID" => "", "categoryID" => "", "bookingDate" => $currentDate, "category" => "Personal", "firstName" => "", "lastName" => "", "bookingStart" => $getMyAgendaDetailsvalue->agenda_start_time, "bookingEnd" => $getMyAgendaDetailsvalue->agenda_end_time, "phone" => $phone, "userImageUrl" => $userImageUrl, "bookingLatitude" => "", "bookingLongitude" => "", "bookingAddress" => "" , "status" => "" , "isAgenda" => 1 , "agendaID" => (int)$getMyAgendaDetailsvalue->id , "agendaText" => $getMyAgendaDetailsvalue->agenda_text , "agendaType" => $getMyAgendaDetailsvalue->agenda_type);
        }


        if(empty($bookingArr)) {
            $json = array("success" => 0, "result" => array(), "error" => "Datos no encontrados");
        } else {
            foreach ($bookingArr as $key => $val) {
                $time[$key] = $val['bookingStart'];
            }

            array_multisort($time, SORT_ASC, $bookingArr);
            $bookingArr = str_replace("null" , '""' , json_encode($bookingArr));
            $json = array("success" => 1, "result" => json_decode($bookingArr), "error" => "No se ha encontrado ningún error");
        }
    }
}
echo json_encode($json);
?>