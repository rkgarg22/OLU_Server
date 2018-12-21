<?php 
include("../../wp-config.php");
require_once "/usr/share/php/Mail.php";
global $wpdb;
//Defining varables

$userID = $_GET['userID'];
if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {

        $firstName = get_user_meta($userID, "first_name", true);
        $lastName = get_user_meta($userID, "last_name", true);
        //SMTP SETUP
        $host = "ssl://smtp.gmail.com";
        $username = "oluappinfo@gmail.com";
        $password = "sergiomauriciogmail18";
        $port = "465";
        $from = "oluappinfo@gmail.com";
        $to = $user->data->user_login;
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

        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='OLU'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3> Hola " . $firstName . " " . $lastName . ",</h3> </td></tr><tr> <td> <h3> Bienvenido a OLU team. Ya eres parte de nuestro gran grupo de especialistas, entrenadores, fisioterapeutas y masajistas. <h3> <h3>A partir de este momento podrás  hacer uso de la OLU App. Para ingresar es muy fácil:  </h3> <ol><li>Asegurate de descargar  la App de Olu. en el App Store o Play Store.</li><li>Ingresa a la plataforma como ENTRENADOR (OLU TEAM)</li><li>Ingresa el email de tu registro y el password que creaste al registrarte.</li></ol> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Muchas gracias,<br>EQUIPO OLU</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";

        $mail = $smtp->send($to, $headers, $message2);

        update_user_meta($userID , "isApprove" , "yes");
        ?>
            <script>
                window.location.href = "http://3.16.104.146/wp-admin";
            </script>
        <?php
    }
}
echo json_encode($json);