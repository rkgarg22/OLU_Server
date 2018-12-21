<?php 


error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../../../wp-config.php');

require_once(ABSPATH . 'wp-admin/includes/media.php');

require_once(ABSPATH . 'wp-admin/includes/file.php');

require_once(ABSPATH . 'wp-admin/includes/image.php');

global $wpdb;

require_once(ABSPATH . 'wp-content/plugins/olu app/inc/reader.php');
$excel = new Spreadsheet_Excel_Reader();
$x = 1;
$excel->read(ABSPATH. "/oluTeam.xls");

$x = 1;
while ($x <= $excel->sheets[0]['numRows']) {
    if($x > 1){
        if (!email_exists($excel->sheets[0]['cells'][$x][6])) {
            $user_id = wp_create_user($excel->sheets[0]['cells'][$x][6], $excel->sheets[0]['cells'][$x][3], $excel->sheets[0]['cells'][$x][6]);
            update_user_meta($user_id , "dob" , $excel->sheets[0]['cells'][$x][4]);
            update_user_meta($user_id , "gender" , $excel->sheets[0]['cells'][$x][5]);
            update_user_meta($user_id , "phone" , $excel->sheets[0]['cells'][$x][7]);
            update_user_meta($user_id , "description" , $excel->sheets[0]['cells'][$x][8]);
        }
    }
    $x++;
}