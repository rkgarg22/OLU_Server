<?php 
include("../../wp-config.php");
global $wpdb;
date_default_timezone_set("America/Bogota");
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $data_body["userID"];
$bookingID = $data_body["bookingID"];
$rating = $data_body["rating"];
$comment = $data_body["comment"];
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
	//checking User
    $user = get_user_by('ID', $userID);
        
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {

        $wpdb->query("UPDATE `wtw_booking` SET `status` = 1 WHERE `id` = $bookingID");
        $getALLData = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id` = $bookingID");
        if ($user->roles[0] == "contributor") {
            $getBookedUser = $getALLData[0]->booking_from;
        } else {
            $getBookedUser = $getALLData[0]->user_id;
        }
        $wpdb->insert('wtw_booking_reviews', array(
            'user_id' => $getBookedUser,
            'booking_id' => $bookingID,
            'review_from' => $userID,
            'rating' => $rating,
            'comments' => $comment,
            'review_created' => date("Y-m-d H:i:s")
        ));
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);