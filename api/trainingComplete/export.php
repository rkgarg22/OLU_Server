<?php 
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=UserRatingHistory.csv');

$fp = fopen('php://output', 'wb');
include("../../wp-config.php");

date_default_timezone_set("America/Bogota");
$curent = date("Y-m-d H:i:s");
$userID = $_GET['userID'];
$bookingSource = $wpdb->get_results("SELECT * FROM `wtw_booking_reviews` WHERE `user_id`  = $userID");
$arrayPrep = array();
$arrayPrep[] = array("bookingFrom" => "Revisión De" , "reviewComment" => "Revisar comentario" , "reviewCount" => "Revisión de recuento" , "reviewCreated" => "Revisión creada");
foreach ($bookingSource as $key => $value) {
    $userData = get_userdata($value->review_from);
    $arrayPrep[] = array("bookingFrom" => $userData->data->user_login, "reviewComment" => $value->comments, "reviewCount" => $value->rating, "reviewCreated" => $value->review_created);
}

foreach ($arrayPrep as $key => $value) {

    fputcsv($fp, $value);
                    # code...
}
fclose($fp);
?>