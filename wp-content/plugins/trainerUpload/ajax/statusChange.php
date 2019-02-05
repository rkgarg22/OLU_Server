<?php 


include('../../../../wp-config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
global $wpdb;

require_once(ABSPATH . 'wp-content/plugins/trainerUpload/inc/reader.php');
$excel = new Spreadsheet_Excel_Reader();
$x = 1;
foreach ($wpdb->get_results("SELECT * FROM `wtw_upload_file` ORDER BY `url_id` DESC Limit 1") as $key => $row) {

    $check = explode(".146/", $row->url);
}

$x = 1;
echo $check[1];
echo $excel->read(ABSPATH . $check[1]);


$x = 1;
while ($x <= $excel->sheets[0]['numRows']) {
    if ($x > 1) {

        echo "<pre>";
        print_r($excel->sheets[0]['cells'][$x]);
        echo "</pre>";
        if (!email_exists($excel->sheets[0]['cells'][$x][6])) {
           /*  $user_id = wp_create_user($excel->sheets[0]['cells'][$x][6], $excel->sheets[0]['cells'][$x][3], $excel->sheets[0]['cells'][$x][6]);
            $u = new WP_User($user_id);

// Add role
            $u->add_role('contributor');
            update_user_meta($user_id, "first_name", $excel->sheets[0]['cells'][$x][1]);
            update_user_meta($user_id, "last_name", $excel->sheets[0]['cells'][$x][2]);
            update_user_meta($user_id, "dob", $excel->sheets[0]['cells'][$x][4]);
            update_user_meta($user_id, "gender", $excel->sheets[0]['cells'][$x][5]);
            update_user_meta($user_id, "phone", $excel->sheets[0]['cells'][$x][7]);
            update_user_meta($user_id, "description", $excel->sheets[0]['cells'][$x][8]);
            update_user_meta($user_id, "isApprove", 'no'); */
        } else {
            $user = get_user_by('email', $excel->sheets[0]['cells'][$x][6]);
            $user_id = $user->ID;
           /*  update_user_meta($user_id, "first_name", $excel->sheets[0]['cells'][$x][1]);
            update_user_meta($user_id, "last_name", $excel->sheets[0]['cells'][$x][2]);
            update_user_meta($user_id, "dob", $excel->sheets[0]['cells'][$x][4]);
            update_user_meta($user_id, "gender", $excel->sheets[0]['cells'][$x][5]);
            update_user_meta($user_id, "phone", $excel->sheets[0]['cells'][$x][7]);
            // update_user_meta($user_id, "description", $excel->sheets[0]['cells'][$x][8]); */
            update_user_meta($user_id, "isApprove", 'no');
        }
    }
    $x++;
}