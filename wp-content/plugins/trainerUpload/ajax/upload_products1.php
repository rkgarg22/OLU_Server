<?php 


error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../../../wp-config.php');

require_once(ABSPATH . 'wp-admin/includes/media.php');

require_once(ABSPATH . 'wp-admin/includes/file.php');

require_once(ABSPATH . 'wp-admin/includes/image.php');

global $wpdb;

require_once(ABSPATH.'wp-content/plugins/ProductImport/inc/reader.php');
$excel = new Spreadsheet_Excel_Reader();
function get_attachment_id_from_src ($image_src) {

  global $wpdb;

  $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";

  $id = $wpdb->get_var($query);

  return $id;

}


foreach( $wpdb->get_results("SELECT * FROM `wtw_upload_file` ORDER BY `url_id` DESC Limit 1") as $key => $row)

{

	$check = explode(".27/",$row->url );
}

$x =1;
$excel->read(ABSPATH.$check[1]);
        while($x<=$excel->sheets[0]['numRows']) {
        	if($x > 1 && $excel->sheets[0]['cells'][$x][4] != "") {
		       echo "SELECT count(`post_id`) FROM `wtw_postmeta` WHERE `meta_key` = 'client_registeration_id' AND `meta_value`= '".$excel->sheets[0]['cells'][$x][1]."'";

		  $countt = $wpdb->get_var("SELECT count(`post_id`) FROM `wtw_postmeta` WHERE `meta_key` = 'client_registeration_id' AND `meta_value`= '".$excel->sheets[0]['cells'][$x][1]."'");
		    $myCheck = $wpdb->get_results("SELECT * FROM `wtw_postmeta` WHERE `meta_key` = 'client_registeration_id' AND `meta_value`= '".$excel->sheets[0]['cells'][$x][1]."'");
		  $valuetest = array();
		 	if(!empty($myCheck)) {
		 		foreach ($myCheck as $key => $valueCheck) {
		 			$terms = wp_get_post_terms( $valueCheck->post_id, "language"  );
		 			if(!empty($terms)) {
 						foreach ($terms as $key => $valueTerms) {
 							if($valueTerms->slug == "es") {
 								$valuetest[] = $valueCheck->post_id;
 							}
 						}
		 			}
		 		}
		 	}
		  if($countt == 0 || empty($valuetest)) {
		  	$post_information = array(
			    'post_title' => iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][4]),
			    'post_content' => iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][22]),
			    'post_type' => 'restaurants',
			    'post_status' => 'publish'
			);
		  	/*$post_information = array(
			    'post_title' => "aaaaaaaaaaaaaa",
			    'post_content' => "bbbbbbbbbbbb",
			    'post_type' => 'restaurants',
			    'post_status' => 'publish'
			);*/
		 $post_id = wp_insert_post( $post_information , true);
		$image = media_sideload_image($excel->sheets[0]['cells'][$x][34], $post_id, "");
		 echo "<pre>";
			print_r($image);
		 echo "</pre>";

            $test_tmpimg = explode("src='" , $image);

            $test_tmp = explode("'" , $test_tmpimg[1]);

            $image_id = get_attachment_id_from_src($test_tmp[0]);
		 if (is_wp_error($post_id)) {
		    $errors = $post_id->get_error_messages();
		    foreach ($errors as $error) {
		        echo "- " . $error . "<br />";
		    }
		}
			if($excel->sheets[0]['cells'][$x][2] == "Activo") {
				$status = "active";
			} else {
				$status = "non active";
			}
			wp_set_post_terms( $post_id, array(5), "language" );
			echo $file12 = str_replace('; ', ';', $excel->sheets[0]['cells'][$x][21]);
			$cateSlug = explode(";" , $file12);
				$intialCategory = array();
			foreach ($cateSlug as $key => $cateSlu) {
				echo "SELECT * FROM `wtw_options` where `option_value` LIKE '%$cateSlu%'";
				$wtw_options = $wpdb->get_results("SELECT * FROM `wtw_options` where `option_value` LIKE '%$cateSlu%'");
				foreach ($wtw_options as $key => $valu) {
					$var = explode("_" , $valu->option_name);
						$intialCategory[] = $var[2];
				}
			}
			wp_set_post_terms( $post_id, $intialCategory, "restaurant_category" );
			
            if($excel->sheets[0]['cells'][$x][12] == "Horario fijo") {
            	$schduled = "yes";
            } else {
            	$schduled = "no";
            }
            add_post_meta( $post_id, '_thumbnail_id', $image_id, true );
			add_post_meta($post_id , "status" , $status , true);
			add_post_meta($post_id , "client_registeration_id" , $excel->sheets[0]['cells'][$x][1] , true);
			add_post_meta($post_id , "schduled" , $schduled , true);
			add_post_meta($post_id , "phone" , $excel->sheets[0]['cells'][$x][5] , true);
			add_post_meta($post_id , "address" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][6] , true));
			add_post_meta($post_id , "city" ,  iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][7] , true));
			add_post_meta($post_id , "neighbour" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][8] , true));
			add_post_meta($post_id , "latitude" , $excel->sheets[0]['cells'][$x][9] , true);
			add_post_meta($post_id , "longitude" , $excel->sheets[0]['cells'][$x][10] , true);
			add_post_meta($post_id , "type_of_offers" , $excel->sheets[0]['cells'][$x][11] , true);
			add_post_meta($post_id , "history" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][23] , true));
			add_post_meta($post_id , "minimum" , $excel->sheets[0]['cells'][$x][26] , true);
			add_post_meta($post_id , "menu" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][25] , true));
			add_post_meta($post_id , "maximum" , $excel->sheets[0]['cells'][$x][27] , true);
			add_post_meta($post_id , "web_url" , $excel->sheets[0]['cells'][$x][30] , true);
			add_post_meta($post_id , "instagram_account" , $excel->sheets[0]['cells'][$x][29] , true);
			add_post_meta($post_id , "registered_date" , $excel->sheets[0]['cells'][$x][3] , true);
			if($excel->sheets[0]['cells'][$x][13] != "" && $myLang != "") {
				$monday = explode(";" , $excel->sheets[0]['cells'][$x][13]);
				foreach ($monday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Tuesday";
					} else {
						$endday = "Monday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Monday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][14] != "" && $myLang != "") {
				$tuesday = explode(";" , $excel->sheets[0]['cells'][$x][14]);

				foreach ($tuesday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Wednesday";
					} else {
						$endday = "Tuesday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Tuesday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][15] != "" && $myLang != "") {
				$wednesday = explode(";" , $excel->sheets[0]['cells'][$x][15]);
				foreach ($wednesday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Thursday";
					} else {
						$endday = "Wednesday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Wednesday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][16] != "" && $myLang != "") {
				$thursday = explode(";" , $excel->sheets[0]['cells'][$x][16]);
				foreach ($thursday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Friday";
					} else {
						$endday = "Thursday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Thursday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][17] != "" && $myLang != "") {
				$friday = explode(";" , $excel->sheets[0]['cells'][$x][17]);
				foreach ($friday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Saturday";
					} else {
						$endday = "Friday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Friday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][18] != "") {
				$saturday = explode(";" , $excel->sheets[0]['cells'][$x][18]);
				foreach ($saturday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Sunday";
					} else {
						$endday = "Saturday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Saturday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][19] != "" && $myLang != "") {
				$sunday = explode(";" , $excel->sheets[0]['cells'][$x][19]);
				foreach ($sunday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Monday";
					} else {
						$endday = "Sunday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Sunday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][20] != "") {
				$Festivos = explode(";" , $excel->sheets[0]['cells'][$x][20]);
				foreach ($Festivos as $key => $value) {
					$workDate = explode("-" , $value);
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Festivos",
						'start_time' => $workDate[0],
						'end_day' => "Festivos",
						'end_time' => $workDate[1],
						'deal_id' => $post_id)
					);
				}
			}
			
			if($excel->sheets[0]['cells'][$x][24] != "") {
			$typeOfFood = explode(";" , $excel->sheets[0]['cells'][$x][24]);
			foreach ($typeOfFood as $key => $value) {
				$wpdb->insert( 'wtw_foofitem', array(
					'restID' => $post_id,
					'name' => iconv('ISO-8859-1','UTF-8', $value))
				);
			}
			}
			if($excel->sheets[0]['cells'][$x][28] != "") {
			$payment = explode(";" , $excel->sheets[0]['cells'][$x][28]);
			foreach ($payment as $key => $value) {
				$wpdb->insert( 'wtw_payment', array(
					'restID' => $post_id,
					'name' => iconv('ISO-8859-1','UTF-8', $value))
				);
			}
			}
			if($excel->sheets[0]['cells'][$x][31] != "") {
			$wtw_facilities = explode(";" , $excel->sheets[0]['cells'][$x][31]);
			foreach ($wtw_facilities as $key => $value) {
				$wpdb->insert( 'wtw_facilities', array(
					'restID' => $post_id,
					'name' => iconv('ISO-8859-1','UTF-8', $value))
				);
			}
			}
		  }  else {
		  	$counttRes = $wpdb->get_results("SELECT * FROM `wtw_postmeta` WHERE `meta_key` = 'client_registeration_id' AND `meta_value`= '".$excel->sheets[0]['cells'][$x][1]."'");
		  	foreach($counttRes as $tes) {
		  		$categories = get_the_terms( (int)$tes->post_id, 'language' );
			if($categories[0]->slug == "es") {
				$myLang = (int)$tes->post_id;
			}
			  }
			  echo $title = $excel->sheets[0]['cells'][$x][4];
			$my_post = array(
				'ID' => $myLang,
				'post_title' => iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][4]) ,
				'post_content' => iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][22]),
			);
			echo "<pre>";
				print_r($my_post);
			echo "</pre>";
			echo  "UPDATE `wtw_posts` SET `post_title` = '$title' WHERE `ID` = $myLang";
			$wpdb->query("UPDATE `wtw_posts` SET `post_title` = '$title' WHERE `ID` = $myLang");

// Update the post into the database
			wp_update_post($my_post);
			if($excel->sheets[0]['cells'][$x][2] == "Activo") {
				$status = "active";
			} else {
				$status = "non active";
			}
			wp_set_post_terms( $myLang, array(5), "language" );
			
			$file12 = str_replace('; ', ';', $excel->sheets[0]['cells'][$x][21]);
			$cateSlug = explode(";" , $file12);
				$intialCategory = array();
			foreach ($cateSlug as $key => $cateSlu) {
				echo "SELECT * FROM `wtw_options` where `option_value` LIKE '%$cateSlu%'";
				$wtw_options = $wpdb->get_results("SELECT * FROM `wtw_options` where `option_value` LIKE '%$cateSlu%'");
				// $wtw_options = array_pop($wtw_options);
				echo "<pre>";
					print_r($wtw_options);
				echo "</pre>";
				foreach ($wtw_options as $key => $valu) {
					$var = explode("_" , $valu->option_name);
						$intialCategory[] = $var[2];
				}
			}
			// echo $myLang;
			wp_set_post_terms( $myLang, $intialCategory, "restaurant_category" );
			echo $excel->sheets[0]['cells'][$x][34];

			$image = media_sideload_image($excel->sheets[0]['cells'][$x][34], $myLang, "");

            $test_tmpimg = explode("src='" , $image);

            $test_tmp = explode("'" , $test_tmpimg[1]);

            $image_id = get_attachment_id_from_src($test_tmp[0]);
            if($excel->sheets[0]['cells'][$x][12] == "Horario fijo") {
            	$schduled = "yes";
            } else {
            	$schduled = "no";
            }
			update_post_meta($myLang , "_thumbnail_id" , $image_id);
			update_post_meta($myLang , "status" , $status);
			update_post_meta($myLang , "client_registeration_id" , $excel->sheets[0]['cells'][$x][1]);
			update_post_meta($myLang , "schduled" , $schduled);
			update_post_meta($myLang , "phone" , $excel->sheets[0]['cells'][$x][5]);
			update_post_meta($myLang , "address" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][6]));
			update_post_meta($myLang , "city" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][7]));
			update_post_meta($myLang , "latitude" , $excel->sheets[0]['cells'][$x][9]);
			update_post_meta($myLang , "longitude" , $excel->sheets[0]['cells'][$x][10]);
			update_post_meta($myLang , "type_of_offers" , $excel->sheets[0]['cells'][$x][11]);
			update_post_meta($myLang , "neighbour" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][8]));
			update_post_meta($myLang , "history" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][23]));
			update_post_meta($myLang , "minimum" , $excel->sheets[0]['cells'][$x][26]);
			update_post_meta($myLang , "menu" , iconv('ISO-8859-1','UTF-8', $excel->sheets[0]['cells'][$x][25]));
			update_post_meta($myLang , "maximum" , $excel->sheets[0]['cells'][$x][27]);
			update_post_meta($myLang , "web_url" , $excel->sheets[0]['cells'][$x][30]);
			update_post_meta($myLang , "instagram_account" , $excel->sheets[0]['cells'][$x][29]);
			update_post_meta($myLang , "registered_date" , $excel->sheets[0]['cells'][$x][3]);
			$wpdb->query("DELETE FROM `wtw_deal_new_timing` WHERE `deal_id` = $myLang");
			$wpdb->query("DELETE FROM `wtw_facilities` WHERE `restID` = $myLang");
			$wpdb->query("DELETE FROM `wtw_payment` WHERE `restID` = $myLang");
			$wpdb->query("DELETE FROM `wtw_foofitem` WHERE `restID` = $myLang");
			$CURdATA = date("y-m-d");
			if($excel->sheets[0]['cells'][$x][13] != "" && $myLang != "") {
				$monday = explode(";" , $excel->sheets[0]['cells'][$x][13]);
				foreach ($monday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Tuesday";
					} else {
						$endday = "Monday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Monday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][14] != "" && $myLang != "") {
				$tuesday = explode(";" , $excel->sheets[0]['cells'][$x][14]);

				foreach ($tuesday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Wednesday";
					} else {
						$endday = "Tuesday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Tuesday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][15] != "" && $myLang != "") {
				$wednesday = explode(";" , $excel->sheets[0]['cells'][$x][15]);
				foreach ($wednesday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Thursday";
					} else {
						$endday = "Wednesday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Wednesday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][16] != "" && $myLang != "") {
				$thursday = explode(";" , $excel->sheets[0]['cells'][$x][16]);
				foreach ($thursday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Friday";
					} else {
						$endday = "Thursday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Thursday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][17] != "" && $myLang != "") {
				$friday = explode(";" , $excel->sheets[0]['cells'][$x][17]);
				foreach ($friday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Saturday";
					} else {
						$endday = "Friday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Friday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][18] != "") {
				$saturday = explode(";" , $excel->sheets[0]['cells'][$x][18]);
				foreach ($saturday as $key => $value) {
					$workDate = explode("-" , $value);

						echo $myLang;
					echo strtotime($CURdATA." ".$workDate[0]);
					echo "<br>";
					echo strtotime($CURdATA." ".$workDate[1]);

					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						echo $myLang;
						echo $endday = "Sunday";
					} else {
						$endday = "Saturday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Saturday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][19] != "" && $myLang != "") {
				$sunday = explode(";" , $excel->sheets[0]['cells'][$x][19]);
				foreach ($sunday as $key => $value) {
					$workDate = explode("-" , $value);
					if(strtotime($workDate[0]) > strtotime($workDate[1])) {
						$endday = "Monday";
					} else {
						$endday = "Sunday";
					}
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Sunday",
						'start_time' => $workDate[0],
						'end_day' => $endday,
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			if($excel->sheets[0]['cells'][$x][20] != "" && $myLang != "") {
				$Festivos = explode(";" , $excel->sheets[0]['cells'][$x][20]);
				foreach ($Festivos as $key => $value) {
					$workDate = explode("-" , $value);
					$wpdb->insert( 'wtw_deal_new_timing', array(
						'start_day' => "Festivos",
						'start_time' => $workDate[0],
						'end_day' => "Festivos",
						'end_time' => $workDate[1],
						'deal_id' => $myLang)
					);
				}
			}
			
			if($excel->sheets[0]['cells'][$x][24] != "-" && $myLang != "") {
			$typeOfFood = explode(";" , $excel->sheets[0]['cells'][$x][24]);
			foreach ($typeOfFood as $key => $value) {
				$wpdb->insert( 'wtw_foofitem', array(
					'restID' => $myLang,
					'name' => iconv('ISO-8859-1','UTF-8', $value))
				);
			}
		}
			if($excel->sheets[0]['cells'][$x][28] != "" && $myLang != "") {
			$payment = explode(";" , $excel->sheets[0]['cells'][$x][28]);
			foreach ($payment as $key => $value) {
				echo $value;
				echo "<br>";
				$wpdb->insert( 'wtw_payment', array(
					'restID' => $myLang,
					'name' => iconv('ISO-8859-1','UTF-8', $value))
				);
			}
			}
			if($excel->sheets[0]['cells'][$x][31] != "" && $myLang != "") {
			$wtw_facilities = explode(";" , $excel->sheets[0]['cells'][$x][31]);
			foreach ($wtw_facilities as $key => $value) {
				$wpdb->insert( 'wtw_facilities', array(
					'restID' => $myLang,
					'name' => iconv('ISO-8859-1','UTF-8', $value))
				);
		  }
		  }
		  }
        	}
	       $x++;
        }
        $wpdb->query("DELETE FROM `wtw_deal_new_timing` WHERE `start_time` = ''");
        $wpdb->query("DELETE FROM `wtw_facilities` WHERE `restID` = ''");
        $wpdb->query("DELETE FROM `wtw_facilities` WHERE `name` = ''");
        $wpdb->query("DELETE FROM `wtw_payment` WHERE `restID` = ''");
        $wpdb->query("DELETE FROM `wtw_foofitem` WHERE `restID` = ''");

        $wpdb->query("DELETE FROM `wtw_facilities` WHERE `restID` = 0");
        $wpdb->query("DELETE FROM `wtw_payment` WHERE `restID` = 0");
        $wpdb->query("DELETE FROM `wtw_foofitem` WHERE `restID` = 0");
        $wpdb->query("UPDATE `wtw_postmeta` SET `meta_value` = '' WHERE `meta_value` = '-'");





