<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$image_url = $_POST['image_url'];
include('../../../../wp-config.php');
global $wpdb;
	$post_id = $wpdb->insert( 'wtw_upload_file', array(
		'url' => $image_url)
		);
		$_SESSION['post_id_file'] = $image_url;