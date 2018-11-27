<?php
include("../../../wp-config.php");
$userID = $_GET['userID'];
$order = $_GET['order'];
$isPaid = $_GET['isPaid'];
$language = $_GET['lang'];

if ($userID == "") {
    $json = array("success" => 0, "result" => array(), "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => array(), "error" => "Usuario Inválido Invalid");
    } else {
        // echo "SELECT * FROM `wtw_booking` WHERE `user_id` = $userID AND `status` = 1 AND `notAttended` != 1 OR `user_id` = $userID AND `isPaid` = 1 ORDER BY `booking_date` $order";
        $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID AND `status` = 1 AND `notAttended` != 1 OR `user_id` = $userID AND `isPaid` = 1 ORDER BY `booking_date` $order");
        
        if (empty($getUserDataBooking)) {
            $json = array("success" => 0, "result" => array(), "error" => "Datos no encontrados");
        } else {
            $bookingArr = array();
            foreach ($getUserDataBooking as $getUserDataBookingkey => $getUserDataBookingvalue) {
                
                $firstNameC = get_user_meta($getUserDataBookingvalue->booking_from, "first_name", true);
                $lastNameC = get_user_meta($getUserDataBookingvalue->booking_from, "last_name", true);
                $phone = get_user_meta($getUserDataBookingvalue->booking_from, "phone", true);
                $userImageUrl = get_user_meta($getUserDataBookingvalue->booking_from, "userImageUrl", true);
                $userCheck = $getUserDataBookingvalue->booking_from;
                //Booking Status
                    $getData  = $wpdb->get_results("SELECT * FROM `wtw_booking_price` WHERE `booking_id` = $getUserDataBookingvalue->id");
                //Booking Status
                $terMyTerm = get_term($getUserDataBookingvalue->category_id, "category");
                // echo apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
               
                if($isPaid == $getData[0]->booking_paid) {
                if ($getUserDataBookingvalue->booking_for == "single") {
                    $section = 1;
                } elseif ($getUserDataBookingvalue->booking_for == "business") {
                    $section = 2;
                } elseif ($getUserDataBookingvalue->booking_for == "business3") {
                    $section = 4;
                } elseif ($getUserDataBookingvalue->booking_for == "business4") {
                    $section = 5;
                } else {
                    $section = 3;
                }
                $pprice = getBookingPriceTrainer($getUserDataBookingvalue->id) - 2;
                   $getMyPrice = $pprice / 100 * 70;
                    if (strpos($getMyPrice, ".") !== false) {
                        $price = number_format((float)$getMyPrice, 3, '.', '');
                    } else {
                        $price = $getMyPrice.".000";
                    }
                    $bookingArr[] = array("userID" => (int)$userCheck, "bookingID" => (int)$getUserDataBookingvalue->id, "date" => $getUserDataBookingvalue->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "categoryID" => (int)$terMyTerm->term_id, "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $getUserDataBookingvalue->booking_start, "bookingEnd" => $getUserDataBookingvalue->booking_end, "bookingType" => $section, "phone" => $phone, "isPaid" => $getData[0]->booking_paid , "amount" => $getThisBooking = $price , "bookingLatitude" => $getUserDataBookingvalue->booking_latitude, "bookingLongitude" => $getUserDataBookingvalue->booking_longitude, "bookingAddress" => $getUserDataBookingvalue->booking_address, "userImageUrl" => $userImageUrl);
                }
                
            }
            if(empty($bookingArr)) {
                $json = array("success" => 0, "result" => array(), "error" => "Datos no encontrados");
            } else {
                $json = array("success" => 1, "result" => $bookingArr, "error" => "No se ha encontrado ningún error");
            }
        }
    }
}
echo json_encode($json);
?>