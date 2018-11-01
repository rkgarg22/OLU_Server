<?php 
include("../../../wp-config.php");
require_once "/usr/share/php/Mail.php";

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
/* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($_REQUEST);
fwrite($myfile, $txt);
fclose($myfile); */
$userID = $_REQUEST['userID'];
$description = $_REQUEST["description"];
$firstName = $_REQUEST["firstName"];
$lastName = $_REQUEST["lastName"];
$gender = $_REQUEST["gender"];
$phone = $_REQUEST["phone"];
$age = $_REQUEST["age"];
$dob = date("Y-m-d", strtotime($_REQUEST["dob"]));
$categories1 = stripcslashes($_REQUEST["categories1"]);
stripcslashes($_REQUEST["categories1"]);
$categories = json_decode($categories1);
$upload_dir = wp_upload_dir();
$date = new DateTime();
$string = $date->getTimestamp();

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        if (!empty($_FILES)) {
            $extArr = explode(".", $_FILES["imageUrl"]["name"]);
            $target_file = $upload_dir['path'] . "/" . $string . '.' . end($extArr);
            move_uploaded_file($_FILES["imageUrl"]["tmp_name"], $target_file);
            $url = $upload_dir['url'] . "/" . $string . "." . end($extArr);
          /*   $wpdb->insert('im_usermeta', array(
                'user_id' => $user_id,
                'meta_key' => "userImageUrl",
                'meta_value' => $url
            )); */
        } else {
            $url = get_user_meta($userID , "userImageUrl" , true);
        }
        $wpdb->insert('wtw_user_update_log', array(
            'user_id' => $userID,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'description' => $description,
            'gender' => $gender,
            'phone' => $phone,
            'age' => $age,
            'dob' => $dob,
            'categories' => $categories1,
            'url' => $url,
            'entry_created' => date("Y-m-d H:i:s"),
            'status' =>0
        ));
       /*  //Updating New Data
        update_user_meta($user_id, 'age', $age);
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'dob', $dob);
        update_user_meta($user_id, 'gender', $gender);
        update_user_meta($user_id, 'description', $description);
        if(!empty($categories)) {
            
            //Deleting Old Prices 
                $wpdb->query("DELETE FROM `wtw_user_pricing` WHERE `user_id` = $userID");
            //Deleting Old Prices 
                foreach ($categories as $key => $value) {
                    $wpdb->insert('wtw_user_pricing', array(
                        'user_id' => $userID,
                        'category_id' => $value->CategryID,
                        'single_price' => $value->singlePrice,
                        'group_price' => $value->groupPrice2,
                        'group_price3' => $value->groupPrice3,
                        'group_price4' => $value->groupPrice4,
                        'company_price' => $value->companyPrice
                    ));
                }
        } */
        $json = array("success" => 1, "result" => 1, "error" => "No se encontró ningún error, el administrador revisará y tomará medidas sobre su solicitud");
    }
}
echo json_encode($json);