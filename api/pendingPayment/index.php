<?php
include("../../wp-config.php");
require_once "/usr/share/php/Mail.php";
global $wpdb;
$rest = json_decode(file_get_contents('php://input'), true);

$txn = $rest['requestId'];
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($rest);
fwrite($myfile, $txt);
fclose($myfile);
$from = "oluappinfo@gmail.com";
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

echo $val = sha1($rest['requestId'] . $rest['status']['status'] . $rest['status']['date'] . '92EukRSJ82Vr0TUt');
if ($rest['status']['status'] == "APPROVED") {
$getPaymentDetails = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `txn_id` = $txn");
if(!empty($getPaymentDetails)) {
    $wpdb->query("UPDATE `wtw_add_money` SET `payment_status` = 1 WHERE `txn_id` = $txn");
} elseif($rest['status']['status'] == "REJECTED") {
    $userID = $getPaymentDetails[0]->user_id;
    $user = get_user_by('ID', $userID);
        $to = $user->data->user_login;
        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> </td></tr><tr> <td> <h3> El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información.  Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago por la aplicación así: <h3> <h3> - ir a mi perfil - seleccionar pagos - métodos de pago - añadir método de pago.  <h3> </td></tr><tr> <td>  </td></tr><tr> <td> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Muchas gracias<br> Equipo OLU!!</b> </strong>   </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
        $mail = $smtp->send($to, $headers, $message2);
        $wpdb->query("UPDATE `wtw_add_money` SET `payment_status` = 0 WHERE `txn_id` = $txn");
}

} else {

    if ($rest['status']['status'] == "REJECTED") {

        $getPaymentDetails = $wpdb->get_results("SELECT * FROM `wtw_add_money` WHERE `txn_id` = $txn");
        $userID = $getPaymentDetails[0]->user_id;
        $user = get_user_by('ID', $userID);
        $to = $user->data->user_login;
        $message2 = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'><html><head> <meta content='text/html; charset=utf-8' http-equiv='Content-Type'/> <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'> <title>Email Template - Classic</title></head><body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; font:12px arial; color:#000;'> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:1px solid'> <tr> <td> <table cellspacing='0' border='0' align='center' cellpadding='0' width='600' style='border:0px solid #ccc; margin-top:0px;'> <tr align='center'> <td style='font-family:arial;padding-bottom:40px; '> <strong> <img src='http://oluapp.com/wp-content/uploads/2018/07/logo_olu_circulo-1.png' alt='Preto'></img> </strong> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='10' width='90%' style='border:0px solid'> <tr> <td> </td></tr><tr> <td> <h3> El método de pago que has ingresado está en estado de RECHAZADO. Por favor comunícate con tu entidad bancaria para verificar la información.  Si deseas continuar con el proceso de OLU, puedes ingresar otro método de pago por la aplicación así: <h3> <h3> - ir a mi perfil - seleccionar pagos - métodos de pago - añadir método de pago.  <h3> </td></tr><tr> <td>  </td></tr><tr> <td> </td></tr></table> <table cellspacing='0' border='0' align='center' cellpadding='0' width='100%' style='border:0px solid #efefef; margin-top:20px; padding:0px;'> <tr> <td align='center' style='font-family:' PT Sans ',sans-serif; font-size:13px; padding:15px 0; border-top:1px solid;'> <b>Muchas gracias<br> Equipo OLU!!</b> </strong>   </td></tr></table> </td></tr></table> <style>td{width: 100%;}</style>";
        $mail = $smtp->send($to, $headers, $message2);
        $wpdb->query("UPDATE `wtw_add_money` SET `payment_status` = 0 WHERE `txn_id` = $txn");
    }
} 