<?php 
include("../../wp-config.php");

global $wpdb;
date_default_timezone_set("America/Bogota");
$userID = $_GET['userID'];
$checkUserID = $_GET['trainerUserID'];
$language = $_GET['lang'];
$category = $_GET['category'];
$currentDate = date("Y-m-d");

if ($userID == ""|| $checkUserID == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
	//checking User
    $user = get_user_by('ID', $userID);
    $checkUser = get_user_by('ID', $checkUserID);
    if (empty($user)) {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido");
    } else {
        if($category == "" || $category == 0) {
            $getTest = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $checkUserID ORDER BY  RAND() LIMIT 1");
        } else {
            $getTest = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $checkUserID AND `category_id` = $category");
        }
        if(empty($getTest)) {
            $json = array("success" => 0, "result" => "", "error" => "Category Not Belong to this user");
        } else {
            $terMyTerm = get_term($category, "category");
            $termName = apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
            $firstName = get_user_meta($checkUserID, "first_name", true);
            $lastName = get_user_meta($checkUserID, "last_name", true);
        //categories

            /* $termName = array();
            $user = 'user_' . $checkUserID;
            $admin_email = get_option('qtranslate_term_name');
            $variable = get_field('categories', $user);
            foreach ($variable as $variablekey => $variablevalue) {
                $terMyTerm = get_term($variablevalue, "category");
                $termName[] = apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
            }
            $category = implode(" , ", $termName); */
            $profileImageURL = get_user_meta($checkUserID, "userImageUrl", true);
            if ($profileImageURL == "") {
                $profileImageURL = " ";
            }
            $price_range = get_field('price_range', $checkUser);
            $user_description = get_field('description', $checkUser);
            $work_experience = get_field('work_experience', $checkUseruser);
            $studies = get_field('studies', $checkUser);
            $certifications = get_field('certifications', $checkUser);
            $checkUserBookied = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = 3");
            if (!empty($checkUserBookied)) {
                $isCurrentlyBooked = 1;
            } else {
                $isCurrentlyBooked = 0;
            }
            $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $checkUserID AND `booking_date` >= '" . $currentDate . "' AND `status` = 3 ORDER BY `booking_date` ASC");
            $bookingArr = array();
            foreach ($getUserDataBooking as $getUserDataBookingkey => $getUserDataBookingvalue) {
                $terMyTerm = get_term($getUserDataBookingvalue->category_id, "category");
                $firstNameC = get_user_meta($checkUserID, "first_name", true);
                $lastNameC = get_user_meta($checkUserID, "last_name", true);
                $single = get_user_meta($checkUserID, "single", true);
                $business = get_user_meta($checkUserID, "business", true);
                $company = get_user_meta($checkUserID, "company", true);
                $price = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $getUserDataBookingvalue->user_id AND `meta_value` = $getUserDataBookingvalue->booking_price");
                if ($price[0]->meta_key == "single") {
                    $section = 1;
                } elseif ($price[0]->meta_key == "business") {
                    $section = 2;
                } else {
                    $section = 3;
                }
                $bookingArr[] = array("date" => $getUserDataBookingvalue->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $getUserDataBookingvalue->booking_start, "bookingEnd" => $getUserDataBookingvalue->booking_end, "bookingType" => $section);
            }
            $latitude = get_user_meta($checkUserID, "latitude", true);
            $longitude = get_user_meta($checkUserID, "longitude", true);
            $phone_number = get_user_meta($checkUserID, "phone_number", true);
            $getCateData = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $checkUserID");
            $arrayOrep = array();
           foreach ($getCateData as $key => $value) {

               $terMyTerm = get_term($value->category_id, "category");
               $arrayOrep[] = array("categoryID" => (int)$value->category_id , "categoryName" => $terMyTerm->name , "singlePrice" => $value->single_price, "groupPrice2" => $value->group_price, "groupPrice3" => $value->group_price3, "groupPrice4" => $value->group_price4, "companyPrice" => $value->company_price ,);
           }
            $userData = array("userID" => (int)$checkUserID, "firstName" => $firstName, "lastName" => $lastName,  "userImageUrl" => $profileImageURL, "price" => "", "description" => strip_tags($user_description), "isCurrentlyBooked" => $isCurrentlyBooked, "bookingDetails" => $bookingArr, "latitude" => $latitude, "longitude" => $longitude, "singlePrice" => $getTest[0]->single_price, "groupPrice2" => $getTest[0]->group_price, "groupPrice3" => $getTest[0]->group_price3, "groupPrice4" => $getTest[0]->group_price4, "companyPrice" => $getTest[0]->company_price , "phone" => $phone_number , "category" => $arrayOrep , "reviews" => getUserRating($trainerUserID));
            $json = array("success" => 1, "result" => $userData, "error" => "No se ha encontrado ningún error");
        }
    }
}
echo json_encode($json);