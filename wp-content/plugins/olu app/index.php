<?php
/*
Plugin Name: OLU APP
Description: OLU APP
Author: Abhinav Grover
*/

if (is_admin())
{   
  function form_create_olu_fitness_section() 
	 {  
	add_menu_page("Olu Fitness", "Olu Fitness",1,"olu_fitness","");
	add_submenu_page("olu_fitness", "Olu Fitness", "Olu Fitness",8, "olu_fitness", "olu_fitness");
	add_submenu_page("olu_fitness", "Olu Fitness Trainer", "Olu Fitness Trainer",8, "trainer", "trainer");
	add_submenu_page("olu_fitness", "Olu Promo Code", "Olu Promo Code",8, "promo_code", "promo_code");
	 }  
   add_action('admin_menu', 'form_create_olu_fitness_section'); 
   
   
   
}
function olu_fitness()
{
include('../wp-config.php');
global $wpdb;

//Global Variable
	$plugin_url = plugin_dir_url(__FILE__);
	$language = get_locale();
	if($language == "es_ES") {
		$lang = "es";
	} else {
		$lang = "en";
	}
//Global Variable

if($_GET['action'] == "bookings"){
	$userID = $_GET['user_id'];
	$bookingSource = "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/bookingHistory/?userID=".$userID."&status=";
	include("users/bookings.php");
} elseif ($_GET['action'] == "wallet") {
		$userID = $_GET['user_id'];
		$bookingSource = "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/payment/paymentHistoryUser/?userID=". $userID ."&order=DESC&lang=es";
		$bookingPayment = file_get_contents($bookingSource);
		$bookingPayment = json_decode($bookingPayment);
		include("users/wallet.php");
	} elseif ($_GET['action'] == "bookingDetails") {

		$bookingID = $_GET['booking_id'];
		$userID = $_GET['user_id'];
		$bookingSource = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id`  = $bookingID");
		$bookingPayment = $bookingSource[0];


		$bookingSource1 = "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/bookingDetails/?userID=" . $userID . "&bookingID=" . $bookingID . "&language=es";
		$bookingPayment1 = file_get_contents($bookingSource1);
		$bookingPayment1 = json_decode($bookingPayment1);
		include("users/bookingDetails.php");
	}
 elseif ($_GET['action'] == "profile") {
		$userID = $_GET['user_id'];
		include("users/userDetails.php");
	} else {
	//Defining to get Roles
	$args = array(
		'role' => 'subscriber'
	);
	$users = get_users($args);
	include("users/userListing.php");
}



}


function trainer()
{
	include('../wp-config.php');
	global $wpdb;

//Global Variable
	$plugin_url = plugin_dir_url(__FILE__);
	$language = get_locale();
	if ($language == "es_ES") {
		$lang = "es";
	} else {
		$lang = "en";
	}
//Global Variable

	if ($_GET['action'] == "user-update") {
		$userID = $_GET['user_id'];
		$dataMy = $wpdb->get_results("SELECT * FROM `wtw_user_update_log` WHERE `user_id` = $userID ORDER BY `id` DESC LIMIT 1");
		// $bookingSource = "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/bookingHistory/?userID=" . $userID . "&status=";
		include("trainer/user-update.php");
	} elseif ($_GET['action'] == "profile") {
		$userID = $_GET['user_id'];
		$data = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $userID");
		include("trainer/trainerDetails.php");
	} elseif ($_GET['action'] == "bookingDetails") {

		$bookingID = $_GET['booking_id'];
		$userID = $_GET['user_id'];
		$bookingSource = $wpdb->get_results("SELECT * FROM `wtw_booking` WHERE `id`  = $bookingID");
		$bookingPayment = $bookingSource[0];


		$bookingSource1 = "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/bookingDetails/?userID=" . $userID . "&bookingID=" . $bookingID . "&language=es";
		$bookingPayment1 = file_get_contents($bookingSource1);
		$bookingPayment1 = json_decode($bookingPayment1);
		include("trainer/bookingDetails.php");
	} elseif ($_GET['action'] == "wallet") {
		$userID = $_GET['user_id'];
		$bookingSource = "http://ec2-13-58-57-186.us-east-2.compute.amazonaws.com/api/bookingHistory/?userID=" . $userID . "&status=";
		include("trainer/bookings.php");
	} elseif ($_GET['action'] == "reviews") {
		$userID = $_GET['user_id'];
		$bookingSource = $wpdb->get_results("SELECT * FROM `wtw_booking_reviews` WHERE `user_id`  = $userID");
		include("trainer/bookingsReviews.php");
	}  else {
	//Defining to get Roles
		$args = array(
			'role' => 'contributor'
		);
		$users = get_users($args);
		include("trainer/trainerListing.php");
	}



}


function promo_code()
{
	include('../wp-config.php');
	global $wpdb;

//Global Variable
	$plugin_url = plugin_dir_url(__FILE__);
	$language = get_locale();
	if ($language == "es_ES") {
		$lang = "es";
	} else {
		$lang = "en";
	}
//Global Variable

include("promo_code/promoListing.php");
}