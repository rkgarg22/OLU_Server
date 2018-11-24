<?php 
include("../../../wp-config.php");
require_once "/usr/share/php/Mail.php";

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

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

        $host = "ssl://smtp.gmail.com";
        $username = "oluappinfo@gmail.com";
        $password = "sergiomauriciogmail18";
        $port = "465";
        $from = "oluappinfo@gmail.com";

        $first_name = get_user_meta($userID, "first_name", true);
        $lastName = get_user_meta($userID, "last_name", true);
        $to = "oluappinfo@gmail.com";
        $headers = array(
            'From' => $from,
            'To' => $to,
            'Content-Type' => "text/html; charset=ISO-8859-1rn",
            'Subject' => "Nueva solicitud para actualizar el perfil de un entrenador"
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

        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='OLU'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3> Hola,</h3> </br> <h3> Equipo OLU:</h3></td></tr><tr> <td> <h3> Han recibido una solicitud de " . $first_name." ".$lastName . " para actualizar su perfil. <h3> <h3>Por favor ingresar al administrador para validar la información.<h3> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Muchas gracias,<br> Admin OLU </b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";


        $mail = $smtp->send($to, $headers, $message2);
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