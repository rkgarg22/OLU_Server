<?php
include("../../wp-config.php");

date_default_timezone_set("America/Bogota");
$curent = date("Y-m-d H:i:s");
$userID = $_GET['userID'];
$status = $_GET['status'];
$order = $_GET['order'];
$language = $_GET['lang'];

if ($userID == "") {
    $json = array("success" => 0, "result" => array(), "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => array(), "error" => "Usuario Inválido");
    } else {

        getMyExpiredBooking($userID);
      if($status == 1) {

            if ($user->roles[0] == "contributor") {
                $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID AND `status` = $status  ORDER BY `booking_date` DESC");

            } else {
                $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = $status  ORDER BY `booking_date` DESC");

            }
      } else {
        if($user->roles[0] == "contributor"){
            $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $userID AND `status` = $status  ORDER BY `booking_date` ASC");
            
        } else {
            $getUserDataBooking = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `booking_from` = $userID AND `status` = $status  ORDER BY `booking_date` ASC");
          
        }
      }
        if(empty($getUserDataBooking)) {
            $json = array("success" => 0, "result" => array(), "error" => "Datos no encontrados");
        } else {
            $bookingArr = array();
            foreach ($getUserDataBooking as $getUserDataBookingkey => $getUserDataBookingvalue) {
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
                if ($status == 1) {

                    $getBookingReview = $wpdb->get_results("SELECT * FROM `wtw_booking_reviews` WHERE `booking_id` = $getUserDataBookingvalue->id AND `review_from` = $getUserDataBookingvalue->booking_from");
                    // echo "SELECT * FROM `wtw_booking_reviews` WHERE `booking_id` = $getUserDataBookingvalue->id AND `review_from` = $getUserDataBookingvalue->booking_from";
                    if(!empty($getBookingReview)) {
                        $review = $getBookingReview[0]->comments;
                        $reviewStar = (int)$getBookingReview[0]->rating;
                    } else {
                        $review = "";
                        $reviewStar = "";
                    }
                } else {
                    $review = "";
                    $reviewStar = "";
                }
                if($status == 0) {
                    if ($user->roles[0] == "contributor") {
                        $firstNameC = get_user_meta($getUserDataBookingvalue->booking_from, "first_name", true);
                        $lastNameC = get_user_meta($getUserDataBookingvalue->booking_from, "last_name", true);
                        $phone = get_user_meta($getUserDataBookingvalue->booking_from, "phone", true);
                        $userCheck = $getUserDataBookingvalue->booking_from;
                        $userImageUrl = get_user_meta($getUserDataBookingvalue->booking_from, "userImageUrl", true);
                    } else {
                        $firstNameC = get_user_meta($getUserDataBookingvalue->user_id, "first_name", true);
                        $lastNameC = get_user_meta($getUserDataBookingvalue->user_id, "last_name", true);
                        $phone = get_user_meta($getUserDataBookingvalue->user_id, "phone", true);
                        $userCheck = $getUserDataBookingvalue->user_id;
                        $userImageUrl = get_user_meta($getUserDataBookingvalue->user_id, "userImageUrl", true);
                    }
                    $getCurrentTime = date("Y-m-d");
                    $start = date_create($getUserDataBookingvalue->booking_created);
                    $end = date_create();
                    $diff = date_diff($start, $end);
                   
                    if ($diff->i <= 15 && $diff->h == 0) {
                        $terMyTerm = get_term($getUserDataBookingvalue->category_id, "category");
                        // echo apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
                        $price = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $getUserDataBookingvalue->user_id AND `meta_value` = $getUserDataBookingvalue->booking_price");
                        /* if ($price[0]->meta_key == "single") {
                            $section = 1;
                        } elseif ($price[0]->meta_key == "business") {
                            $section = 2;
                        } else {
                            $section = 3;
                        } */
                        $bookingArr[] = array("userID" => (int)$userCheck, "bookingID" => (int)$getUserDataBookingvalue->id, "bookingDate" => $getUserDataBookingvalue->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "categoryID" => (int)$terMyTerm->term_id, "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $getUserDataBookingvalue->booking_start, "bookingEnd" => $getUserDataBookingvalue->booking_end, "bookingType" => $section, "phone" => $phone, "userImageUrl" => $userImageUrl, "bookingLatitude" => $getUserDataBookingvalue->booking_latitude, "bookingLongitude" => $getUserDataBookingvalue->booking_longitude, "bookingAddress" => $getUserDataBookingvalue->booking_address , "review" => $review , "starRating" => $reviewStar, "bookingTime" => strtotime($getUserDataBookingvalue->booking_date." ". $getUserDataBookingvalue->booking_start) , "bookingFor" => $section);
                    } else {
                        $wpdb->query("UPDATE `wtw_booking` SET `status` = 5, `booking_action_time` = '$curent' WHERE `id` = $getUserDataBookingvalue->id");
                    }
                } else {
                    if ($user->roles[0] == "contributor") {
                        $firstNameC = get_user_meta($getUserDataBookingvalue->booking_from, "first_name", true);
                        $lastNameC = get_user_meta($getUserDataBookingvalue->booking_from, "last_name", true);
                        $phone = get_user_meta($getUserDataBookingvalue->booking_from, "phone", true);
                        $userCheck = $getUserDataBookingvalue->booking_from;
                        $userImageUrl = get_user_meta($getUserDataBookingvalue->booking_from, "userImageUrl", true);
                    } else {
                        $firstNameC = get_user_meta($getUserDataBookingvalue->user_id, "first_name", true);
                        $lastNameC = get_user_meta($getUserDataBookingvalue->user_id, "last_name", true);
                        $phone = get_user_meta($getUserDataBookingvalue->user_id, "phone", true);
                        $userCheck = $getUserDataBookingvalue->user_id;
                        $userImageUrl = get_user_meta($getUserDataBookingvalue->user_idID, "userImageUrl", true);
                    }
                    $terMyTerm = get_term($getUserDataBookingvalue->category_id, "category");
                    // echo apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
                    $price = $wpdb->get_results("SELECT * FROM `wtw_usermeta` WHERE `user_id` = $getUserDataBookingvalue->user_id AND `meta_value` = $getUserDataBookingvalue->booking_price");
                   /*  if ($price[0]->meta_key == "single") {
                        $section = 1;
                    } elseif ($price[0]->meta_key == "business") {
                        $section = 2;
                    } else {
                        $section = 3;
                    } */
                    $bookingArr[] = array("userID" => (int)$userCheck, "bookingID" => (int)$getUserDataBookingvalue->id, "bookingDate" => $getUserDataBookingvalue->booking_date, "category" => apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0), "categoryID" => (int)$terMyTerm->term_id, "firstName" => $firstNameC, "lastName" => $lastNameC, "bookingStart" => $getUserDataBookingvalue->booking_start, "bookingEnd" => $getUserDataBookingvalue->booking_end, "bookingType" => $section, "phone" => $phone, "userImageUrl" => "$userImageUrl", "bookingLatitude" => $getUserDataBookingvalue->booking_latitude, "bookingLongitude" => $getUserDataBookingvalue->booking_longitude, "bookingAddress" => $getUserDataBookingvalue->booking_address , "bookingCreated" => $getUserDataBookingvalue->booking_created , "bookingAccepted" => $getUserDataBookingvalue->booking_action_time, "review" => $review, "starRating" => $reviewStar , "bookingTime" => strtotime($getUserDataBookingvalue->booking_date." ". $getUserDataBookingvalue->booking_start), "bookingFor" => $section);
                }

                
            }
            if(!empty($bookingArr)) {
                if ($status != 1) {
                    foreach ($bookingArr as $key => $row) {
                     // replace 0 with the field's index/key
                        $dates[$key] = $row['bookingTime'];
                    }
                    array_multisort($dates, SORT_ASC, $bookingArr);
                } else {
                    foreach ($bookingArr as $key => $row) {
                     // replace 0 with the field's index/key
                        $dates[$key] = $row['bookingTime'];
                    }
                    array_multisort($dates, SORT_DESC, $bookingArr);
                }
                foreach ($bookingArr as $i => $v) {
                        unset($bookingArr[$i]['bookingTime']);
                }
                $bookingArr = str_replace("null", '""', json_encode($bookingArr));
                $json = array("success" => 1, "result" => json_decode($bookingArr), "error" => "No se ha encontrado ningún error");
            } else {
                $json = array("success" => 0, "result" => array(), "error" => "Datos no encontrados");
            }
        }
    }
}
echo json_encode($json);
?>