<?php 
include("../../../wp-config.php");

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

$userID = $_REQUEST["userID"];
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

$upload_dir = wp_upload_dir();
$date = new DateTime();
$string = $date->getTimestamp();
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido Invalid");
    } else {
        if (!empty($_FILES)) {
            $extArr = explode(".", $_FILES["imageUrl"]["name"]);
            $target_file = $upload_dir['path'] . "/" . $string . '.' . end($extArr);
            move_uploaded_file($_FILES["imageUrl"]["tmp_name"], $target_file);
            $url = $upload_dir['url'] . "/" . $string . "." . end($extArr);
        } else {
            $url = "";
        }
        update_user_meta($userID, "userImageUrl", $url);
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);