<?php 
include("../../../wp-config.php");
global $wpdb;
$data_body = json_decode(file_get_contents("php://input"), true);

$userName = $data_body["emailAddress"];
$password = $data_body["password"];
$firebaseTokenId = $data_body["firebaseTokenId"];
$deviceType = $data_body["deviceType"];
$userType = $data_body["userType"];
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = file_get_contents("php://input");
fwrite($myfile, $txt);
fclose($myfile);
if($userType == 1) {
    $role = "contributor";
} else {
    $role = "subscriber";
}


if ($userName != "" && $password != "") {

    $user = get_user_by('login', $userName);
    // && $user->roles[0] == $role
    if (email_exists($userName) && $user->roles[0] == $role) {
        $user = get_user_by('login', $userName);
        
        $myUserRole = $user->roles[0];
        $isActive = get_user_meta($user->data->ID, "isActive", true);
        if ($isActive == "") {
            $isActive = 1;
        }
        if($isActive == 1) {

            if ($user && wp_check_password($password, $user->data->user_pass, $user->ID)) {
               /*  echo "<pre>";
                    print_r($user);
                echo "</pre>"; */
                $userID = $user->data->ID;

                $first_name = get_user_meta($user->data->ID, "first_name", true);
                $last_name = get_user_meta($user->data->ID, "last_name", true);
                $latitude = get_user_meta($user->data->ID, "latitude", true);
                $longitude = get_user_meta($user->data->ID, "longitude", true);
                $pushNotification = get_user_meta($user->data->ID, "pushNotification", true);
                $userImageUrl = get_user_meta($user->ID, "userImageUrl", true);
                $dob = get_user_meta($user->ID, "dob", true);
                $gender = get_user_meta($user->ID, "gender", true);
                $phone = get_user_meta($user->ID, "phone", true);
                $description = get_user_meta($user->ID, "description", true);
                $email_Address = $user->data->user_email;

                if ($myUserRole == "author") {
                    $role = "manager";
                } elseif ($myUserRole == "editor") {
                    $role = "employees";
                }

                update_user_meta($user->data->ID, 'firebaseTokenId', $firebaseTokenId);
                update_user_meta($user->data->ID, 'deviceType', $deviceType);
                //Seting Up Roles
                if ($myUserRole == "contributor") {
                    $userRole = 1; //Trainer
                    $isApprove = get_user_meta($user->ID, "isApprove", true);
                    if (!empty($isApprove) && $isApprove == "yes") {
                        $isApprove = 1;
                        update_user_meta($user->data->ID, 'isOnline', 1);
                    } else {
                        $isApprove = 0;
                        // $isApprove = 1;
                    }
                } elseif ($myUserRole == "subscriber") {
                    $userRole = 2; //User
                    $isApprove = 1;

                    update_user_meta($user->data->ID, 'isOnline', 1);
                } else {
                    $userRole = 0;// others
                    $isApprove = 1;
                    update_user_meta($user->data->ID, 'isOnline', 1);

                }
                $cateData = array();
                // echo "SELECT * FROM  `wtw_user_pricing` WHERE `user_id` = $user->ID";
                $arrayData = $wpdb->get_results("SELECT * FROM  `wtw_user_pricing` WHERE `user_id` = $user->ID");
                foreach ($arrayData as $key => $value) {
                    $cateData[] = array("CategryID" => (int)$value->category_id, "singlePrice" => $value->single_price, "groupPrice2" => $value->group_price, "groupPrice3" => $value->group_price3, "groupPrice4" => $value->group_price4, "companyPrice" => $value->company_price);
                }


                $arrayData = array("userID" => (int)$user->ID, "firstName" => $first_name, "lastName" => $last_name, "emailAddress" => $userName, "userImageUrl" => $userImageUrl, "latitude" => $latitude, "longitude" => $longitude, "role" => $userRole, "dob" => $dob, "gender" => $gender, "phone" => $phone, "isApprove" => $isApprove, "categories" => $cateData, "description" => $description);

                $arrayData = str_replace('"null"', '""', json_encode($arrayData));
                $arrayData = str_replace("null", '""', $arrayData);
                $json = array("success" => 1, "result" => json_decode($arrayData), "error" => "No se ha encontrado ningún error");

            } else {
                $json = array("success" => 0, "result" => null, "error" => "Por favor revisa la contraseña.");
            }
        } else {
            $json = array("success" => 0, "result" => null, "error" => "El usuario no está activo");
        }

    } else {
        $json = array("success" => 0, "result" => null, "error" => "Usuario inválido");
    }
} else {
    if($userName == "") {
        $status = "Correo electrónico perdido";
    } else {
        $status = "Falta la contraseña";
    }
    $json = array("success" => 0, "result" => null, "error" => $status);
}
echo json_encode($json)
?>