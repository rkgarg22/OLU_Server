<?php
include("../../../wp-config.php");
$userID = $_GET['userID'];

if ($userID == "") {
    $json = array("success" => 0, "result" => null, "error" => "Todos los campos son obligatorios");
} else {
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => null, "error" => "Usuario Inválido Invalid");
    } else {
        $firstName = get_user_meta($user->data->ID, "first_name", true);
        $lastName = get_user_meta($user->data->ID, "last_name", true);
        $latitude = get_user_meta($user->data->ID, "latitude", true);
        $longitude = get_user_meta($user->data->ID, "longitude", true);
        $pushNotification = get_user_meta($user->data->ID, "pushNotification", true);
        $userImageUrl = get_user_meta($user->ID, "userImageUrl", true);
        $dob = get_user_meta($user->ID, "dob", true);
        $gender = get_user_meta($user->ID, "gender", true);
        $phone = get_user_meta($user->ID, "phone", true);
        $description = get_user_meta($user->ID, "description", true);
        $email_Address = $user->data->user_email;
        $arrayData = $wpdb->get_results("SELECT * FROM  `wtw_user_pricing` WHERE `user_id` = $user->ID");
        foreach ($arrayData as $key => $value1) {
            $terMyTerm = get_term($value1->category_id, "category");
            $arrayOrep[] = array("CategryID" => (int)$value1->category_id, "categoryName" => $terMyTerm->name, "singlePrice" => $value1->single_price, "groupPrice2" => $value1->group_price, "groupPrice3" => $value1->group_price3, "groupPrice4" => $value1->group_price4, "companyPrice" => $value1->company_price, );
        }

        $arrayDataC = array("userID" => (int)$userID, "firstName" => $firstName, "lastName" => $lastName, "emailAddress" => $user->data->user_email, "userImageUrl" => $userImageUrl, "latitude" => $latitude, "longitude" => $longitude, "description" => $description, "phone" => $phone, "dob" => $dob, "gender" => $gender, "category" => $arrayOrep , "reviews" => (int)getUserRating($_GET['userID']));

        $arrayData1 = str_replace('"null"', '""', json_encode($arrayDataC));
        $arrayData2 = str_replace("null", '""', $arrayData1);
        $json = array("success" => 1, "result" => json_decode($arrayData2), "error" => "No se ha encontrado ningún error");
      
    }
}
echo json_encode($json);
?>