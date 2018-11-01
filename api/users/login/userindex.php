<?php 
include("../../../wp-config.php");
global $wpdb;
// $data_body = json_decode(file_get_contents("php://input"), true);
$userName = $_GET["emailAddress"];
$password = $_GET["password"];
$firebaseTokenId = $_GET["firebaseTokenId"];
$deviceType = $_GET["deviceType"];
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = urlencode($_GET);
fwrite($myfile, $txt);
fclose($myfile);


if ($userName != "" && $password != "") {
    if (username_exists($userName)) {
        $user = get_user_by('login', $userName);
        $myUserRole = $user->roles[0];
        if ($user && wp_check_password($password, $user->data->user_pass, $user->ID)) {
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
            $email_Address = $user->data->user_email;

            if ($myUserRole == "author") {
                $role = "manager";
            } elseif ($myUserRole == "editor") {
                $role = "employees";
            }

            update_user_meta($user->data->ID, 'firebaseTokenId', $firebaseTokenId);
            update_user_meta($user->data->ID, 'deviceType', $deviceType);
            update_user_meta($user->data->ID, 'isOnline', 1);
                //Seting Up Roles
            if ($myUserRole == "contributor") {
                $userRole = 1; //Trainer
                $isApprove = get_user_meta($user->ID, "isApprove", true);
                if (!empty($isApprove) && $isApprove == "yes") {
                    $isApprove = 1;
                } else {
                        // $isApprove = 0;
                    $isApprove = 1;
                }
            } elseif ($myUserRole == "subscriber") {
                $userRole = 2; //User
                $isApprove = 1;
            } else {
                $userRole = 0;// others
                $isApprove = 1;
            }


            $arrayData = array("userID" => (int)$user->ID, "firstName" => $first_name, "lastName" => $last_name, "emailAddress" => $userName, "userImageUrl" => $profileImageURL1, "latitude" => $latitude, "longitude" => $longitude, "role" => $userRole, "dob" => $dob, "gender" => $gender, "phone" => $phone, "isApprove" => $isApprove);

            $arrayData = str_replace('"null"', '""', json_encode($arrayData));
				// $arrayData = str_replace("false",'""',json_encode($arrayData));
            $json = array("success" => 1, "result" => json_decode($arrayData), "error" => "No Error Found");

        } else {
            $json = array("success" => 0, "result" => null, "error" => "Incorrect Password");
        }

    } else {
        $json = array("success" => 0, "result" => null, "error" => "Invalid Username");
    }
} else {
    if ($userName == "") {
        $status = "Email Missing";
    } else {
        $status = "Password Missing";
    }
    $json = array("success" => 0, "result" => null, "error" => $status);
}
echo json_encode($json)
?>