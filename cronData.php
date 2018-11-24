<?php 
include("wp-config.php");
global $wpdb;

date_default_timezone_set("America/Bogota");
$currentTyme = date("Y-m-d H:i:s");
$getRes = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `status` = 0");
echo "<pre>";
    print_r($getRes);
echo "</pre>";
foreach ($getRes as $key => $value) {

    $BookingType = $value->booking_created;
    $end = date_create($BookingType);
    $start = date_create();
    $diff = date_diff($start, $end);

    if ($diff->i >= 15) {
        if ($value->booking_for == "single") {
            $section = 1;
        } elseif ($value->booking_for == "business") {
            $section = 2;
        } elseif ($value->booking_for == "business3") {
            $section = 4;
        } elseif ($value->booking_for == "business4") {
            $section = 5;
        } else {
            $section = 3;
        }
        $wpdb->query("UPDATE `wtw_booking` SET `status` = 6 , `booking_action_time` = '$currentTyme' WHERE `id` = $value->id");
        //UserData
        $categoryData = get_term_by('id', $value->category_id, 'category');
        $firstName = get_user_meta($value->user_id, "first_name", true);
        $lastName = get_user_meta($value->user_id, "last_name", true);
        $phone = get_user_meta($value->user_id, "phone", true);

        $userImageUrl = get_user_meta($value->user_id, "userImageUrl", true);
        $firebaseTokenId = get_user_meta($value->booking_from, "firebaseTokenId", true);
        $title = "OLU";
        $message = "Lo sentimos! " . $firstName . " no puede realizar la actividad.";

        $sendMessageData = sendMessageData($firebaseTokenId, $title, $message, $value->id, $firstName, $lastName, $value->category_id, $categoryData->name, $value->booking_date, $phone, $value->booking_start, $value->booking_end, 6, $value->booking_address, $value->booking_latitude, $value->booking_longitude, $userImageUrl, $section, $value->booking_created, $currentTyme);
        echo "2 Min Set";
    }
}
echo "Abhinav";
?>