<?php 
include("../../wp-config.php");
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
        update_user_meta($userID , "isApprove" , "yes");
        ?>
            <script>
                window.location.href = "http://3.16.104.146/wp-admin";
            </script>
        <?php
    }
}
echo json_encode($json);