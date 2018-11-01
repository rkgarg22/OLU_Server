<?php 
// echo dirname(__DIR__) . "/html/wp-config.php";
require_once "Mail.php";
// require_once(dirname(__DIR__)."/html/wp-config.php");
//SMTP SETUP
$getData = file_get_contents("http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/cronData.php");

$host = "ssl://smtp.gmail.com";
$username = "oluappinfo@gmail.com";
$password = "sergiomauriciogmail18";
$port = "465";
$from = "oluappinfo@gmail.com";
$to = "grover.abhinav82@gmail.com ";
$subject = "aaaaaaaaaaaaaaaaaaaa";
$headers = array(
    'From' => $from,
    'To' => $to,
    'Content-Type' => "text/html; charset=ISO-8859-1rn",
    'Subject' => $subject
);
$smtp = Mail::factory(
    'smtp',
    array(
        'host' => $host,
        'auth' => true,
        'port' => $port,
        'username' => $username,
        'password' => $password
    )
);

// $mail = $smtp->send($to, $headers, $getData);
/* die();
global $wpdb;

date_default_timezone_set("America/Bogota");
$currentTyme = date("Y-m-d H:i:s");
$getRes = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `status` = 0");
foreach ($getRes as $key => $value) {
    
   $BookingType = $value->booking_created;
    $end = date_create($BookingType);
    $start = date_create();
    $diff = date_diff($start, $end);

    if ($diff->i >= 15) {
        $wpdb->query("UPDATE `wtw_booking` SET `status` = 6 , `booking_action_time` = '$currentTyme' WHERE `id` = $value->id");
        //UserData
        $categoryData = get_term_by('id', $value->category_id, 'category');
        $firstName = get_user_meta($value->user_id, "first_name", true);
        $lastName = get_user_meta($value->user_id, "last_name", true);
        $phone = get_user_meta($value->user_id, "phone", true);

        $userImageUrl = get_user_meta($value->user_id, "userImageUrl", true);
        $firebaseTokenId = get_user_meta($value->booking_from, "firebaseTokenId", true);
        $title = "OLU Fitness APP";
        $message = "Su reserva con " . $firstName . " ha expirado";

        $sendMessageData = sendMessageData($firebaseTokenId, $title, $message, $value->id, $firstName, $lastName, $value->category_id, $categoryData->name, $value->booking_date, $phone, $value->booking_start, $value->booking_end, 5, $value->booking_address, $value->booking_latitude, $value->booking_longitude, $userImageUrl, $section);
       
    }
} */
?>
