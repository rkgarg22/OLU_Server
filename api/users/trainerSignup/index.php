<?php 
include("../../../wp-config.php");
require_once "/usr/share/php/Mail.php";

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

$firstName = $_REQUEST["firstName"];
$lastName = $_REQUEST["lastName"];
$emailAddress = sanitize_email($_REQUEST["emailAddress"]);
$password = $_REQUEST["password"];
$latitude = $_REQUEST["latitude"];
$userType = 1;
$longitude = $_REQUEST["longitude"];
$firebaseTokenId = $_REQUEST["firebaseTokenId"];
$deviceType = $_REQUEST["deviceType"];
$gender = $_REQUEST["gender"];
$phone = $_REQUEST["phone"];
$age = $_REQUEST["age"];
$dob = $_REQUEST["dob"];
$description = $_REQUEST["description"];
$workExperiance = $_REQUEST["workExperiance"];
$certifications = $_REQUEST["certifications"];
$studies = $_REQUEST["studies"];
$singlePrice = $_REQUEST["singlePrice"];
$groupPrice = $_REQUEST["groupPrice"];
$companyPrice = $_REQUEST["companyPrice"];
$hearAboutUs = $_REQUEST["hearAboutUs"];
$categories = stripcslashes($_REQUEST["categories"]);
$categories = json_decode($categories);

$upload_dir = wp_upload_dir();
$date = new DateTime();
$string = $date->getTimestamp();


//SMTP SETUP
$host = "ssl://smtp.gmail.com";
$username = "oluappinfo@gmail.com";
$password = "sergiomauriciogmail18";
$port = "465";
$from = "oluappinfo@gmail.com";
$to = "oluappinfo@gmail.com";
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


$message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='OLU'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3> Hola " . $firstName . " ". $lastName. ",</h3> </td></tr><tr> <td> <h3> Muchas gracias por registrarte como especialista de OLU. En los próximos días te estaremos contactando para seguir el proceso de vinculación.  <h3> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Saludos,<br>Equipo OLU</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
// $mail = $smtp->send($to, $headers, $body);
//SMTP SETUP
/* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($_FILES);
fwrite($myfile, $txt);
fclose($myfile); */

if (!empty($_FILES)) {
    $extArr = explode(".", $_FILES["imageUrl"]["name"]);
    $target_file = $upload_dir['path'] . "/" . $string . '.' . end($extArr);
    move_uploaded_file($_FILES["imageUrl"]["tmp_name"], $target_file);
    $url = $upload_dir['url'] . "/" . $string . "." . end($extArr);
} else {
    $url = "";
}
if ($emailAddress != "" && $password != "") {
    if(empty($categories)) {
        $json = array("success" => 0, "result" => null, "error" => "Categoris Required");
    } else {
        if (email_exists($emailAddress)) {
            $json = array("success" => 0, "result" => null, "error" => "La dirección de correo ya existe");
        } else {
            $user_id = wp_create_user($emailAddress, $_REQUEST["password"], $emailAddress);
            // echo $_REQUEST["password"];
            wp_set_password($_REQUEST["password"], $user_id);
            $u = new WP_User($user_id);
            if ($userType == 1) {
                $u->set_role('contributor');
            } else {
                $u->set_role('subscriber');
            }
            update_user_meta($user_id, "first_name", $firstName);
            update_user_meta($user_id, "last_name", $lastName);
            update_user_meta($user_id, "userImageUrl", $url);

            update_user_meta($user_id, "facebookId", $facebookId);
            update_user_meta($user_id, "firebaseTokenId", $firebaseTokenId);
            update_user_meta($user_id, 'deviceType', $deviceType);
            update_user_meta($user_id, 'age', $age);
            update_user_meta($user_id, 'phone', $phone);
            update_user_meta($user_id, 'dob', $dob);
            update_user_meta($user_id, 'gender', $gender);
            update_user_meta($user_id, 'description', $description);
            foreach ($categories as $key => $value) {
                $wpdb->insert('wtw_user_pricing', array(
                    'user_id' => $user_id,
                    'category_id' => $value->CategryID,
                    'single_price' => $value->singlePrice,
                    'group_price' => $value->groupPrice2,
                    'group_price3' => $value->groupPrice3,
                    'group_price4' => $value->groupPrice4,
                    'company_price' => $value->companyPrice
                ));
            }
            $wpdb->insert('im_usermeta', array(
                'user_id' => $user_id,
                'meta_key' => "userImageUrl",
                'meta_value' => $url
            ));
            $profileImageURL = get_user_meta($user_id, "userImageUrl", true);
            if ($profileImageURL == "" || !isset($profileImageURL)) {
                $profileImageURL = "";
            }
            update_user_meta($user_id, "latitude", $latitude);
            update_user_meta($user_id, "longitude", $longitude);
            update_user_meta($user_id, "isApprove", "no");

            update_user_meta($user_id, "isOnline", 0);
            $userStatus = get_user_meta($user_id, "userStatus", true);
            $arrayData = array("userID" => (int)$user_id, "firstName" => $firstName, "lastName" => $lastName, "emailAddress" => $emailAddress, "userImageUrl" => $profileImageURL, "latitude" => $latitude, "longitude" => $longitude, "role" => $userType, "description" => $description, "phone" => $phone, "dob" => $dob , "gender" => $gender, "categories"=> $categories);


            $message1 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html> <head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title> </head> <body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center' > <td style='font-family:arial;padding-bottom:40px; '><strong><img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img></strong></td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3>Hola Administrador,</h3> </td></tr><tr> <td> <h3> New Trainer acaba de registrarse en OLU, amablemente revise y apruebe la cuenta desde la cuenta de administrador, los detalles del usuario son <h3> </td></tr><tr> <td> <table cellspacing='0' border='0' cellpadding='0' width='100%'> <tr><td align='right' height='20' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-top:1px solid #efefef; border-bottom:1px solid #efefef; border-right:1px solid #efefef; border-left:1px solid #efefef;padding-top:8px; padding-bottom:8px; padding-left:8px; padding-right:8px;'><strong>Nombre :</strong></td><td height='20' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; padding:8px;'>.$firstName.''.$lastName.</td></tr><tr><td align='right' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; border-top:0px; padding:8px;'><strong>Email :</strong></td><td style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-right:1px solid #efefef; border-bottom:1px solid #efefef; padding:8px;'>.$emailAddress.</td></tr><tr><td align='right' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; border-top:0px; padding:8px;'><strong>Aprobar aqui :</strong></td><td style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-right:1px solid #efefef; border-bottom:1px solid #efefef; padding:8px;'><a href='http://3.16.104.146/api/trainerApprove/?userID=" . $user_id . "'>Haga clic aquí</a></td></tr></table> </td><td width='30'></td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr><td align='center' style='font-family:'PT Sans',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>OLU</b></strong></td></tr></table> </td></tr></table> <style>td{width:100%;}</style>";
            $mail = $smtp->send($to, $headers, $message1);
            $mail = $smtp->send($emailAddress, $headers, $message2);
            $json = array("success" => 1, "result" => $arrayData, "error" => "No se ha encontrado ningún error");
        }
    }
    

} else {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
}
echo json_encode($json);

?>