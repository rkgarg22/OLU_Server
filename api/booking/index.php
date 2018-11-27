<?php 

include("../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
//Defining varables

date_default_timezone_set("America/Bogota");
$userID = $_GET['userID'];
$bookinguserID = $_GET['bookinguserID'];
$categoryID = $_GET['categoryID'];
$bookingDate = $_GET['bookingDate'];
$bookingTime = $_GET['bookingTime'];
$priceGroup = $_GET['bookingType'];
$address = $_GET['address'];
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];
$currentDate = date("Y-m-d");
 $StartDateSet = $bookingDate." ".$bookingTime;

$strToTimeCheck = strtotime($StartDateSet);
$categoryData = get_term_by('id', $categoryID, 'category');
$myWallet = getUserWallet($userID);
if ($categoryID == 8) {
    $endDateSet = date("Y-m-d H:i:s", strtotime('+90 minutes', strtotime($bookingDate." ". $bookingTime)));
    $endDateSetNoti = date("H:i:s", strtotime('+90 minutes', strtotime($bookingDate." ". $bookingTime)));
} else {
  $endDateSet = date("Y-m-d H:i:s", strtotime('+1 hours', strtotime($bookingDate . " " . $bookingTime)));
    $endDateSetNoti = date("H:i:s", strtotime('+1 hours', strtotime($bookingDate . " " . $bookingTime)));
}
if ($priceGroup == 1) {
    $section = "single";
    $testName = "single_price";
} elseif ($priceGroup == 2) {
    $section = "business";
    $testName = "group_price";
} elseif ($priceGroup == 4) {
    $section = "business3";
    $testName = "group_price3";
} elseif ($priceGroup == 5) {
    $section = "business4";
    $testName = "group_price4";
} else {
    $section = "Company";
    $testName = "company_price";
}
$bookingDate = date("Y-m-d" , strtotime($bookingDate));
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios", "isTokenSaved" => 0, "paymentRequire" => 0);
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido", "isTokenSaved" => 0, "paymentRequire" => 0);
    } else {

        $dateCheck = date("Y-m-d", strtotime($bookingDate));
        $getBookingCheck = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $bookinguserID AND `booking_date` = '$dateCheck' AND `status` = 3");
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
    $getMyBookingAvailable = getMyBookingAvailable($bookinguserID, $bookingDate, $bookingTime, $endDateSetNoti);
    $getMyAgendaAvailable = getMyAgendaAvailable($bookinguserID, $bookingDate, $bookingTime, $endDateSetNoti);
     
        if ($getMyAgendaAvailable == "False" || $stat == "False") {
            $stat = "False";
        }
        if($stat == "False")  {
            $json = array("success" => 0, "result" => 0, "error" => "El entrandor está presentando una OLU actividad en este momento.", "isTokenSaved" =>1, "paymentRequire" => 0);
        } else {
            $dataCheckOne = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $bookinguserID AND `category_id` = $categoryID");
            if($dataCheckOne[0]->$testName == "" || (int)$dataCheckOne[0]->$testName <= 0) {
                $json = array("success" => 0, "result" => 0, "error" => "El OLU que has seleccionado no está disponible bajo el parámetro de clase grupal que has seleccionado", "isTokenSaved" => 1, "paymentRequire" => 0);
            } else {
            ///Payment Check First
                if($myWallet < $dataCheckOne[0]->$testName) {
                    $paymentStatus = 1;
                } else {
                    $paymentStatus = 0;
                }
                $bookingCheck = getUserToken($userID);
                //Promocode 

                $promoCode = get_user_meta($userID, "promoCode", true);
                if($promoCode != "") {
                    $getPromoCode = $wpdb->get_results("SELECT * FROM `wtw_promocode` WHERE `name` = '$promoCode'");
                    $couponStart = $getPromoCode[0]->start_data;
                    if ($currentDate >= date("Y-m-d", strtotime($couponStart))) {
                        $promo = $promoCode;
                    }

                } else {
                    $promo = "";
                }
                //Promo code
                if ($bookingCheck != "False" && strpos($bookingCheck, "False") == false) {
                    $wpdb->insert('wtw_booking', array(
                        'user_id' => $_GET['bookinguserID'],
                        'category_id' => $categoryID,
                        'booking_date' => $bookingDate,
                        'booking_start' => $bookingTime,
                        'booking_end' => $endDateSet,
                        'booking_from' => $userID,
                        'booking_created' => date("Y-m-d H:i:s"),
                        'booking_for' => $section,
                        'transaction_id' => '',
                        'booking_latitude' => $latitude,
                        'booking_longitude' => $longitude,
                        'booking_address' => $address,
                        'status' => 0, // ... and so on
                        'promocode' => $promo, // ... and so on
                        'isPaid' => 0, // ... and so on
                    ));
                    $createdDate = date("Y-m-d H:i:s");
                    $lastid = $wpdb->insert_id;
                    $firstName = get_user_meta($userID, "first_name", true);
                    $promoCode = get_user_meta($userID, "promoCode", true);
                    $lastName = get_user_meta($userID, "last_name", true);
                    $phone = get_user_meta($userID, "phone", true);
                    $userImageUrl = get_user_meta($userID, "userImageUrl", true);
                    $firebaseTokenId = get_user_meta($bookinguserID, "firebaseTokenId", true);
                    $title = "OLU";
                    $message = "Tienes una nueva solicitud de ".$firstName ;
                    $data = array("bookingID" => $lastid, "name" => $firstName, "categoryID" => $categoryID, "categoryID" => $categoryID, "categoryName" => $categoryData->name, "day" => $bookingDate . " " . $bookingTime, "address" => $address);
                    $data = json_encode($data);
                    $priceGroup1 = 0;

                    sendMessageData($firebaseTokenId, $title, $message, $lastid, $firstName, $lastName, $categoryID, $categoryData->name, $bookingDate, $phone, $bookingTime . ":00", $endDateSetNoti, $priceGroup1, $address, $latitude, $longitude, $userImageUrl, $priceGroup, $createdDate, "");

                    update_user_meta($userID, "promoCode", "");
                    $json = array("success" => 1, "result" => 1, "error" => "No Error Found", "isTokenSaved" => 1 , "paymentRequire" => $paymentStatus);
            } else {
                if($bookingCheck == "False") {
                    $mes = "Sin tarjetas guardadas";
                } else {
                    $mes = str_replace("False" , '' , $bookingCheck);
                }
                    $json = array("success" => 0, "result" => 0, "error" => $mes, "isTokenSaved" => 0, "paymentRequire" => 0);
                }
            } 
        }
        
        ///Payment Check First
    }
}
echo json_encode($json);