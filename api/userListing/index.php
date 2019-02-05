<?php 
include("../../wp-config.php");

global $wpdb;


/* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($_GET);
fwrite($myfile, $txt);
fclose($myfile); */
date_default_timezone_set("America/Bogota");

$userID = $_GET['userID'];
$searchText = $_GET['searchText'];
$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];
$category = $_GET['category'];
$offSet = $_GET['offSet'];
$date = $_GET['date'];
$time = $_GET['time'];
$language = $_GET['lang'];
$gender = $_GET['gender'];
$selectGroup = $_GET['selectGroup'];
$StartDateSet = $date . " " . $time;
$strToTimeCheck = strtotime($StartDateSet);
$currentTime = time();
/* $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = json_encode($_GET);
fwrite($myfile, $txt);
fclose($myfile);
 */
//Prepairing Data
if($category  == 8) {
    $endDateSet = date("Y-m-d H:i:s", strtotime('+90 minutes', strtotime($StartDateSet)));

    $endDateAgenda = date("H:i:s", strtotime('+90 minutes', strtotime($StartDateSet)));
} else {
    $endDateSet = date("Y-m-d H:i:s", strtotime('+1 hours', strtotime($StartDateSet)));
    $endDateAgenda = date("H:i:s", strtotime('+1 hours', strtotime($StartDateSet)));
}
//Prepairing Data

if ($userID == "") {
    $json = array("success" => 0, "result" => "", "error" => "Todos los campos son obligatorios");
} else {
	//checking User
    $user = get_user_by('ID', $userID);
    if (empty($user)) {
        $json = array("success" => 0, "result" => "", "error" => "Usuario Inválido");
    } else {
        if($currentTime > $strToTimeCheck) {
            $json = array("success" => 0, "result" => "", "error" => "Time is Invlid or passed away");
        } else {
            $difference = round(abs($strToTimeCheck - $currentTime) / 3600, 2);
            $args = array(
                'role' => 'contributor',
            );
            $getUserListingAreray = get_users($args);
            $finArr = array();
            foreach ($getUserListingAreray as $key => $value) {
                
                unset($statusTrue);
                if($_GET['category'] == "") {
                    $category = "";
                } else {
                    $category = $_GET['category'];
                }
                if($searchText == "") {
                    $statusTrue =  "true";
                } else {
                    $firstName = get_user_meta($value->ID, "first_name", true);
                    $lastName = get_user_meta($value->ID, "last_name", true);
                    $email = $value->data->user_email;
                    if (strpos(strtolower($firstName), strtolower($searchText)) !== false) {
                        $statusTrue = "true";
                    }
                    if (strpos(strtolower($lastName), strtolower($searchText)) !== false) {
                        $statusTrue = "true";
                    }
                    if (strpos(strtolower($email), strtolower($searchText)) !== false) {
                        $statusTrue = "true";
                    }
                }
                $stat = "True";
                $userLatitude = get_user_meta($value->ID, "latitude", true);
                $useLongitude = get_user_meta($value->ID, "longitude", true);
                $isOnline = get_user_meta($value->ID, "isOnline", true);
                $isActive = get_user_meta($value->ID, "isActive", true);
                $isApprove = get_user_meta($value->ID, "isApprove", true);
                if($isActive == "") {
                    $isActive = 1;
                }
                if($_GET['latitude'] == "" && $_GET['longitude'] == "") {
                    $distance = 1;
                } else {
                    $distance = distance(floatval($latitude), floatval($longitude), floatval($userLatitude), floatval($useLongitude), "K");
                }
                if ($distance <= 100 && $isOnline == 1 && $isActive == 1 && $isApprove == "yes" && $statusTrue == "true") {
                   /*  $user = 'user_' . $value->ID;
                    $variable = get_field('categories', $user); */
                    if(empty($category)) {
                        $userrID = $value->data->ID;
                        $getTest = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $userrID ORDER BY `id` ASC LIMIT 1");
                        $category = (int)$getTest[0]->category_id;
                        if($selectGroup == "") {

                            if ($getTest[0]->single_price != "") {
                                $price = $getTest[0]->single_price;
                            } elseif ($getTest[0]->group_price != "") {
                                $price = $getTest[0]->group_price;
                            } elseif ($getTest[0]->group_price3 != "") {
                                $price = $getTest[0]->group_price3;
                            } elseif ($getTest[0]->group_price4 != "") {
                                $price = $getTest[0]->group_price4;
                            } else {
                                $price = $getTest[0]->company_price;
                            }
                        } else {
                            if ($selectGroup == 1) {
                                $price = $getTest[0]->single_price;
                            } elseif ($selectGroup == 2) {
                                $price = $getTest[0]->group_price;
                            } elseif ($selectGroup == 4) {
                                $price = $getTest[0]->group_price3;
                            } elseif ($selectGroup == 5) {
                                $price = $getTest[0]->group_price4;
                            } else {
                                $price = $getTest[0]->company_price;
                            }
                        }
                        
                    } else {
                        $use = $value->data->ID;
                        $cat = $_GET['category'];
                        $getTest = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $use AND `category_id` = $cat");
                        if($use == 241) {

                        }
                        if ($selectGroup == "") {
                            if ($getTest[0]->single_price != "") {
                                $price = $getTest[0]->single_price;
                            } elseif ($getTest[0]->group_price != "") {
                                $price = $getTest[0]->group_price;
                            } elseif ($getTest[0]->group_price3 != "") {
                                $price = $getTest[0]->group_price3;
                            } elseif ($getTest[0]->group_price4 != "") {
                                $price = $getTest[0]->group_price4;
                            } else {
                                $price = $getTest[0]->company_price;
                            }
                        } else {
                            if ($selectGroup == 1) {
                                $price = $getTest[0]->single_price;
                            } elseif ($selectGroup == 2) {
                                $price = $getTest[0]->group_price;
                            } elseif ($selectGroup == 4) {
                                $price = $getTest[0]->group_price3;
                            } elseif ($selectGroup == 5) {
                                $price = $getTest[0]->group_price4;
                            } else {
                                $price = $getTest[0]->company_price;
                            }
                        }
                        
                       /*  if ($use == 241) {
                            echo $price;
                        } */

                       /*  if ($getTest[0]->single_price != "") {
                            $price = $getTest[0]->single_price;
                        } elseif ($getTest[0]->group_price != "") {
                            $price = $getTest[0]->group_price;
                        } elseif ($getTest[0]->group_price3 != "") {
                            $price = $getTest[0]->group_price3;
                        } elseif ($getTest[0]->group_price4 != "") {
                            $price = $getTest[0]->group_price4;
                        } else {
                            $price = $getTest[0]->company_price;
                        } */
                    }
                   
                    $getTest = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->ID AND `category_id` = $category");
                    if (!empty($getTest)) {
                        $terMyTerm = get_term($category, "category");
                        $termName = apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
                     //Getiing Booking Check
                        $dateCheck = date("Y-m-d", strtotime($date));
                        $getBookingCheck = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `user_id` = $value->ID AND `booking_date` = '$dateCheck' AND `status` = 3 OR `status` = 4");
                        if($date != "") {

                            /* foreach ($getBookingCheck as $getBookingCheckkey => $getBookingCheckvalue) {
                                $startDateCheck = $getBookingCheckvalue->booking_date . " " . $getBookingCheckvalue->booking_start;
                                $endDateCheck = $getBookingCheckvalue->booking_date . " " . $getBookingCheckvalue->booking_end;
                                if (($strToTimeCheck > strtotime($startDateCheck)) && ($strToTimeCheck < strtotime($endDateCheck))) {
                                    $stat = "False";
                                }
                                if ((strtotime($endDateSet) > strtotime($startDateCheck)) && (strtotime($endDateSet) < strtotime($endDateCheck))) {
                                    $stat = "False";
                                }
                                
                            }
                            $getMyAgendaAvailable = getMyAgendaAvailable($userID, $date, $time, $endDateAgenda);
                            if($getMyAgendaAvailable == "False") {
                                $stat = "False";
                            } */
                        }
                    //Getiing Booking Check

                        $firstName = get_user_meta($value->ID, "first_name", true);
                        $lastName = get_user_meta($value->ID, "last_name", true);
                        $genderU = get_user_meta($value->ID, "gender", true);
                        $description = get_user_meta($value->ID, "description", true);
                        $latitude = get_user_meta($value->ID, "latitude", true);
                        $longitude = get_user_meta($value->ID, "longitude", true);
                        $profileImageURL = get_user_meta($value->ID, "userImageUrl", true);
                        if ($profileImageURL == "") {
                            $profileImageURL = " ";
                        }
                    // $termName = array();
                        // $admin_email = get_option('qtranslate_term_name');
                    // echo "<pre>";
                    // print_r($admin_email);
                    // echo "</pre>";
                    /* foreach ($variable as $variablekey => $variablevalue) {
                       
                        $terMyTerm = get_term($variablevalue, "category");
                        // echo $admin_email[$terMyTerm->name][$language];
                        $termName[]  = apply_filters('translate_text', $terMyTerm->name, $lang = $language, $flags = 0);
                    } */
                        if ($gender != "") {
                            if (strtolower($gender) != strtolower($genderU)) {
                                $stat = "False";
                            }
                        }

                        
                        if ($stat == "True" && $price != "") {

                            $getCateData = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $value->ID");
                            $arrayOrep = array();
                            foreach ($getCateData as $key => $value1) {

                                $terMyTerm = get_term($value1->category_id, "category");
                                $arrayOrep[] = array("categoryID" => (int)$value1->category_id, "categoryName" => $terMyTerm->name, "singlePrice" => $value1->single_price, "groupPrice2" => $value1->group_price, "groupPrice3" => $value1->group_price3, "groupPrice4" => $value1->group_price4, "companyPrice" => $value1->company_price, );
                            }
                            $finArr[] = array("userID" => $value->ID, "firstName" => $firstName, "lastName" => $lastName, "categoryName" => $termName, "categoryID" => (int)$category, "price" => $price, "userImageUrl" => $profileImageURL, "reviews" => getUserRating($trainerUserID), "latitude" => $latitude, "longitude" => $longitude , "timeDifference" => $difference, "category" => $arrayOrep , "distance" => $distance);
                        }
                    }
                }
            }
            if (!empty($finArr)) {
                foreach ($finArr as $key => $row) {
                     // replace 0 with the field's index/key
                    $dates[$key] = $row['distance'];
                }
                array_multisort($dates, SORT_ASC, $finArr);
                /* foreach ($finArr as $i => $v) {
                    unset($finArr[$i]['distance']);
                } */
                /* if ($offset == 1) {
                    $offset = 0;
                } else {
                    $offset = ($offset - 1) * 20;
                }
                $finArr = array_slice($finArr, $offset, 20); */
                $finArr = str_replace("null" , '""' , json_encode($finArr));
                $json = array("success" => 1, "result" => json_decode($finArr), "error" => "No se ha encontrado ningún error");
            } else {
                $json = array("success" => 0, "result" => null, "error" => "Datos no encontrados");
            }
        }
    }
}
echo json_encode($json);