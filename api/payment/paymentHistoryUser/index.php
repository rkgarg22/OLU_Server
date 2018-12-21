<?php
include("../../../wp-config.php");

$userID = $_GET['userID'];
$order = $_GET['order'];
$language = $_GET['lang'];

if ($userID == "") {
    $json = array("success" => 0, "result" => array(), "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => array(), "error" => "Usuario Inválido Invalid");
    } else {
        //Add Wallet Section
        $getWalletInfo = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `user_id` = $userID AND `moneyPlan` != 'custom'");
        $arrayGet = array();
        foreach ($getWalletInfo as $key => $value) {
            if($value->moneyPlan == 1) {
                $categoryName = "OLU Basic";
            }elseif($value->moneyPlan == 3) {
                $categoryName = "OLU Premium";
            } else {
                $categoryName = "OLU Standar";
            }
            if($value->payment_status == 1) {
                $paymentStatus = "Approved";
            } elseif($value->payment_status == 0){
                $paymentStatus = "Rejected";
            }
            $arrayGet[]  = array("date" => date("d/m/Y" , strtotime($value->created_date)) , "categoryName" => $categoryName , "amount" => $value->moneyValue.".000" , "time" => date ("H:i:s", strtotime($value->created_date)), "bookingTime" => strtotime($value->created_date) , "reference" => "$value->ref_num" , "paymentStatus" => (int)$value->payment_status);
            // $arrayGet[] = array("paymentID" => $value->id , "moneyPlan" => $value->moneyPlan , "moneyValue" => $value->moneyValue , "txnID" => $value->txn_id , "orderDate" => $value->created_date , "unix" => strtotime($value->created_date), "userID" => "", "bookingID" => "", "date" => "", "category" => "", "categoryID" => "", "firstName" => "", "lastName" => "", "bookingStart" => "", "bookingEnd" => "", "bookingType" => "", "phone" => "", "amount" => "" , "recordType" => 1, "bookingLatitude" => "", "bookingLongitude" => "", "bookingAddress" => "", "userImageUrl" => "" , "price" => "" , "categoryName" => "OLU  Standar" , "date"=> $value->created_date);
        }
        //Booking Section
        // echo "SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 1 OR `booking_from` = $userID AND  `isPaid` = 1 ORDER BY `booking_date` DESC ";
        $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 1 OR `booking_from` = $userID AND  `isPaid` = 1 ORDER BY `booking_date` DESC ");
        foreach ($getUserDataBooking as $key => $value) {
            
            $firstNameC = get_user_meta($value->user_id, "first_name", true);
            $lastNameC = get_user_meta($value->user_id, "last_name", true);
            $phone = get_user_meta($value->user_id, "phone", true);
            $userImageUrl = get_user_meta($value->user_id, "userImageUrl", true);
            $userCheck = $value->user_id;
            $terMyTerm = get_term($value->category_id, "category");
            $price = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $value->user_id AND `meta_value` = $value->booking_price");
            $refID = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `bookingID` = $value->id");
            if(!empty($refID)) {
                (string)$ref_num = $refID[0]->ref_num;

            } else {
                $ref_num = "";
            }
            if ($price[0]->meta_key == "single") {
                $section = 1;
            } elseif ($price[0]->meta_key == "business") {
                $section = 2;
            } else {
                $section = 3;
            }
            $myWalletThen =  getUserWalletBefore($value->booking_from , $value->id);
          $getBookingPrice = getBookingPriceTrainer($value->id);
            if(!empty($refID)) {
                if($myWalletThen < 0) {
                    $myWalletThen = 0;
                } 
                $price = $refID[0]->moneyValue;
          /*       echo $getBookingPrice;
                echo "<br>";
                echo $myWalletThen;
                echo "<br>";
                echo $price;
                echo "<br>";
                echo apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
                echo "<br>"; */
                $arrayGet[] = array("date" => date("d/m/Y", strtotime($value->booking_date)), "categoryName" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "amount" => $price . ".000", "time" => date("H:i:s", strtotime($value->booking_action_time)), "bookingTime" => strtotime($value->booking_action_time), "reference" => $ref_num , "paymentStatus" => 1);
            } else {
                $price = $getBookingPrice;
            }
            //$arrayGet[] = array("paymentID" => "", "moneyPlan" => "", "moneyValue" => "", "txnID" => "", "orderDate" => $value->booking_date . " " . $value->booking_end, "unix" => strtotime($value->booking_date." ".$value->booking_end) , "userID" => $userCheck, "bookingID" => (int)$value->id, "date" => $value->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "categoryID" => (int)$terMyTerm->term_id, "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $value->booking_start, "bookingEnd" => $value->booking_end, "bookingType" => $section, "phone" => $phone, "amount" => $getThisBooking = getBookingPrice($value->id) , "userImageUrl" => $userImageUrl , "recordType" => 2, "bookingLatitude" => $value->booking_latitude, "bookingLongitude" => $value->booking_longitude, "bookingAddress" => $value->booking_address);
        }

        $price = array();
        foreach ($arrayGet as $key => $row) {
                     // replace 0 with the field's index/key
            $dates[$key] = $row['bookingTime'];
        }
        array_multisort($dates, SORT_DESC, $arrayGet);
        foreach ($arrayGet as $i => $v) {
            unset($arrayGet[$i]['bookingTime']);
        }
        $json = array("success" => 1, "result" => $arrayGet, "error" => "No se ha encontrado ningún error");
        }
    // }
}
echo json_encode($json);
?>