<?php 

function generateRandomString($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
include("../../../wp-config.php");
require_once "/usr/share/php/Mail.php";
global $wpdb;
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $_GET["emailAddress"];

if ($userID == "" ) {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('email', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        $newPassword = generateRandomString(6);
        $firstName =  get_user_meta($user->ID , "first_name" , true);
        //Mail SetUp
        //SMTP SETUP
        $from = "oluappinfo@gmail.com";
        $host = "ssl://smtp.gmail.com";
        $username = "oluappinfo@gmail.com";
        $password = "sergiomauriciogmail18";
        $port = "465";

       $to = $user->data->user_email;
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
        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html> <head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title> </head> <body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> <h3> Hola (a) ".$firstName. ",</h3> </td></tr><tr><td><h3>Hemos recibido tú solicitud de obtener una nueva contraseña. </h3></td> <td> <h3> Esta es tu nueva contraseña:  ". $newPassword . "<h3> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Gracias por hacer parte de OLU.</b> </strong> </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";

        $mail = $smtp->send($to, $headers, $message2);
        wp_set_password($newPassword, $user->ID);
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
        //Mail SetUp

    }
}
echo json_encode($json);