<?php 
include("../../../wp-config.php");
/* print_r($_FILES);
print_r($_REQUEST); */

/* $json = array("success" => 0, "result" => $_FILES , "error" =>  $_REQUEST);
echo json_encode($json);
die(); */

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');


// $data_body = json_decode(file_get_contents("php://input"), true);
/* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($_FILES);
fwrite($myfile, $txt);
fclose($myfile);
$myfile = fopen("newfile2.txt", "w") or die("Unable to open file!");
$txt = json_encode($_REQUEST);
fwrite($myfile, $txt);
fclose($myfile); */
$userID = $_REQUEST["userID"];
$firstName = $_REQUEST["firstName"];
$lastName = $_REQUEST["lastName"];
$dob = $_REQUEST["dob"];
$gender = $_REQUEST["gender"];
$phone = $_REQUEST["phone"];
$upload_dir = wp_upload_dir();
$date = new DateTime();
$string = $date->getTimestamp();

if ($userID == "") {
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido Invalid");
    } else {
        update_user_meta($user->data->ID, 'first_name', $firstName);
        update_user_meta($user->data->ID, 'last_name', $lastName);
        update_user_meta($user->data->ID, 'dob', $dob);
        update_user_meta($user->data->ID, 'gender', $gender);
        update_user_meta($user->data->ID, 'phone', $phone);
        if (!empty($_FILES)) {
            $extArr = explode(".", $_FILES["imageUrl"]["name"]);
            $target_file = $upload_dir['path'] . "/" . $string . '.' . end($extArr);
            move_uploaded_file($_FILES["imageUrl"]["tmp_name"], $target_file);
            $url = $upload_dir['url'] . "/" . $string . "." . end($extArr);
            update_user_meta($user->data->ID, 'userImageUrl', $url);
        } else {
            $url = get_user_meta($userID , "userImageUrl" , true);
        }

        $json = array("success" => 1, "result" => $url, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);

?>