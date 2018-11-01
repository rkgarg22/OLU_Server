<?php 
include("../../wp-config.php");
global $wpdb;
//Defining varables
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $data_body['userID'];
$firstName = $data_body['firstName'];
$lastName = $data_body['lastName'];
$emailAddress = $data_body['emailAddress'];
$phone = $data_body['phone'];
$message = $data_body['message'];
$subject = $data_body['subject'];

if ($userID == "") {
    $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else {
        require_once "/usr/share/php/Mail.php";

        $from = "comunidadpreto@gmail.com";
// $to = "comunidadpreto@gmail.com";
        $to = "grover.abhinav82@gmail.com";
        $subject = "OLU Fitness";
        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head><meta content='text/html; charset=utf-8' http-equiv='Content-Type'/><link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'><title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'><table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'><tr><td><table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'><tr align='center' ><td style='font-family:arial;padding-bottom:40px; '><strong><img src='http://ec2-13-59-34-53.us-east-2.compute.amazonaws.com/webservices/logo.png' alt='Preto'></img></strong></td></tr></table><table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'><tr><td><h3>Equipo olu.</h3></td></tr><tr><td><h3>Este mensaje se ha enviado desde un formulario de contacto en olu - especialistas en deporte <h3></td></tr><tr><td><table cellspacing='0' border='0' cellpadding='0' width='100%'><tr><td align='right' height='20' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-top:1px solid #efefef; border-bottom:1px solid #efefef; border-right:1px solid #efefef; border-left:1px solid #efefef;padding-top:8px; padding-bottom:8px; padding-left:8px; padding-right:8px;'><strong>Nombre :</strong></td><td height='20' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; padding:8px;'>.$firstName.''.$lastName.</td></tr><tr><td align='right' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; border-top:0px; padding:8px;'><strong>Email :</strong></td><td style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-right:1px solid #efefef; border-bottom:1px solid #efefef; padding:8px;'>.$emailAddress.</td></tr><tr><td align='right' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; border-top:0px; padding:8px;'><strong>Teléfono :</strong></td><td style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-right:1px solid #efefef; border-bottom:1px solid #efefef; padding:8px;'>.$phone.</td></tr><tr><td align='right' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; border-top:0px; padding:8px;'><strong>Asunto :</strong></td><td style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-right:1px solid #efefef; border-bottom:1px solid #efefef; padding:8px;'>.$subject.</td></tr><tr><td align='right' style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border:1px solid #efefef; border-top:0px; padding:8px;'><strong>Mensaje :</strong></td><td style='width:20%; font-family:'PT Sans',sans-serif; font-size:13px; border-right:1px solid #efefef; border-bottom:1px solid #efefef; padding:8px;'>.$message.</td></tr></table></td><td width='30'></td></tr></table><table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'><tr><td align='center' style='font-family:'PT Sans',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>OLU APP</b></strong></td></tr></table></td></tr></table><style>td{width:100%;}</style>";

        $host = "ssl://smtp.gmail.com";
        $username = "oluappinfo@gmail.com";
        $password = "sergiomauriciogmail18";
        $port = "465";

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
        $mail = $smtp->send($to, $headers, $body);
        $json = array("success" => 1, "result" => 1, "error" => "No se ha encontrado ningún error");
    }
}
echo json_encode($json);
?>