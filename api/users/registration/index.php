<?php 
include("../../../wp-config.php");
require_once "/usr/share/php/Mail.php";

global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');


$data_body = json_decode(file_get_contents("php://input"), true);
$userImageUrl = $data_body['userImageUrl'];
$firstName = $data_body["firstName"];
$lastName = $data_body["lastName"];
$emailAddress = sanitize_email($data_body["emailAddress"]);
$password = $data_body["password"];
$facebookId = $data_body["facebookId"];
$latitude = $data_body["latitude"];
$dob = $data_body["dob"];
$phone = $data_body["phone"];
$gender = $data_body["gender"];
$userType = $data_body["userType"];
$longitude = $data_body["longitude"];
$firebaseTokenId = $data_body["firebaseTokenId"];
$deviceType = $data_body["deviceType"];
if ($emailAddress != "") {
    if (email_exists($emailAddress)) {
        $user = get_user_by("email", $emailAddress);
        $userID = $user->data->ID;
        $first_name = get_user_meta($user->data->ID, "first_name", true);
        $last_name = get_user_meta($user->data->ID, "last_name", true);
        $email_Address = $user->data->user_email;
        $profileImageURL = get_user_meta((int)$userID, "userImageUrl", true);

        if ($profileImageURL == "") {
            $profileImageURL = "";
        }

        if ($firebaseTokenId != "") {
            update_user_meta($user->data->ID, 'firebaseTokenId', $firebaseTokenId);
        }
        if ($facebookId != "") {
            $facebookIdold = get_user_meta($userID, "facebookId", true);
            $profileImageURL1 = get_user_meta($userID, "userImageUrl", true);
            $first_name = get_user_meta($userID, "first_name", true);
            $last_name = get_user_meta($userID, "last_name", true);
            update_user_meta($user->data->ID, 'latitude', $latitude);
            update_user_meta($user->data->ID, 'longitude', $longitude);
            update_user_meta($user->data->ID, 'deviceType', $deviceType);
            update_user_meta($user->data->ID, 'phone', $phone);
            $dob = get_user_meta($user->ID, "dob", true);
            $phone = get_user_meta($user->ID, "phone", true);
            $gender = get_user_meta($user->ID, "gender", true);
            $role = ( array )$user->roles;
            $role = $role[0];
           
            if ($role == "contributor") {
                $userRole = 1; //Trainer
            } elseif ($role == "subscriber") {
                $userRole = 2; //User
            } else {
                $role = 0;// others
            }
            $arrayData = array("userID" => (int)$userID, "firstName" => $first_name, "lastName" => $last_name, "emailAddress" => $email_Address, "userImageUrl" => $profileImageURL1, "latitude" => $latitude, "longitude" => $longitude , "role" => $userRole, "dob" => $dob, "gender" => $gender, "phone" => $phone);

            $json = array("success" => 1, "result" => $arrayData, "error" => "No se ha encontrado ningún error");
        } else {
            $json = array("success" => 0, "result" => null, "error" => "La dirección de correo ya existe");
        }
    } else {

        if ($facebookId != "") {
            if ($password != "") {
                $password = $password;
            } else {
                $password = "Admin123#@";
            }
        } else {
            $password = $password;
        }
        $user_id = wp_create_user($emailAddress, $password, $emailAddress);
        $u = new WP_User($user_id);
        if ($facebookId != "") {
            update_user_meta($user_id , "userImageUrl" , true);
        }
            $u->set_role('subscriber');
        update_user_meta($user_id, "first_name", $firstName);
        update_user_meta($user_id, "last_name", $lastName);
        $getUserPriofileWay = get_user_meta($user_id, "userImageUrl", true);
        if ($getUserPriofileWay != "") {
            update_user_meta($user_id, "userImageUrl", $data_body['userImageUrl']);
        }
       
        update_user_meta($user_id, "facebookId", $facebookId);
        update_user_meta($user_id, "firebaseTokenId", $firebaseTokenId);
        update_user_meta($user_id, 'deviceType', $deviceType);
        update_user_meta($user_id, 'dob', $dob);
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'gender', $gender);
        $wpdb->insert('im_usermeta', array(
            'user_id' => $user_id,
            'meta_key' => "userImageUrl",
            'meta_value' => $data_body['userImageUrl']
        ));
        $profileImageURL = get_user_meta($user_id, "userImageUrl", true);
        $phone = get_user_meta($user_id, "phone", true);
        if ($profileImageURL == "" || !isset($profileImageURL)) {
            $profileImageURL = "";
        }
        update_user_meta($user_id, "latitude", $latitude);
        update_user_meta($user_id, "longitude", $longitude);
        $userStatus = get_user_meta($user_id, "userStatus", true);
        $arrayData = array("userID" => (int)$user_id, "firstName" => $firstName, "lastName" => $lastName, "emailAddress" => $emailAddress, "userImageUrl" => $profileImageURL, "latitude" => $latitude, "longitude" => $longitude, "role" => $userType, "phone" => $phone , "dob" => $dob, "gender" => $gender);
        //SMTP SETUP
        $from = "oluappinfo@gmail.com";
        $host = "ssl://smtp.gmail.com";
        $username = "oluappinfo@gmail.com";
        $password = "sergiomauriciogmail18";
        $port = "465";
        $subject = "Bienvenido a OLU.";
        $to = $emailAddress;
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
        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3> Bienvenido (a) ".$firstName." ". $lastName. ",</h3> </td></tr><tr> <td> <h3> Ya haces parte de nuestra aplicación OLU, especialmente diseñada para el mejoramiento del cuerpo y la mente. <h3> <h3>En tus manos ya tienes la posibilidad de escoger actividad, día, hora, lugar y un especialista OLU para una sesión de cualquiera de nuestras diferentes actividades. <h3> </td></tr><tr> <td> <h3> Reserva tu sesión en tres simples pasos <h3> </td></tr><tr> <td> <ol> <li>Ingresa a la App</li><li>Escoge actividad, día, hora, lugar y especialista OLU</li><li>Realiza tu actividad de una manera ágil y confiable.</li></ol> </td></tr><tr> <td> <h3> Día a día podrás ver como tu cuerpo y mente se van sintonizando para lograr tus metas. <h3> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Bienvenido a OLU!!</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
        $mail = $smtp->send($to, $headers, $message2);
        $json = array("success" => 1, "result" => $arrayData, "error" => "No se ha encontrado ningún error");
    }

} else {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
}
echo json_encode($json);

?>