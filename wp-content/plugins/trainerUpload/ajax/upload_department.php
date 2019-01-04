<?php 


error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../../../wp-config.php');

require_once(ABSPATH . 'wp-admin/includes/media.php');

require_once(ABSPATH . 'wp-admin/includes/file.php');

require_once(ABSPATH . 'wp-admin/includes/image.php');

global $wpdb;

require_once(ABSPATH . 'wp-content/plugins/departmentUpload/inc/reader.php');
$excel = new Spreadsheet_Excel_Reader();
function get_attachment_id_from_src($image_src)
{

    global $wpdb;

    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";

    $id = $wpdb->get_var($query);

    return $id;

}


foreach ($wpdb->get_results("SELECT * FROM `wtw_upload_file` ORDER BY `url_id` DESC Limit 1") as $key => $row) {

    $check = explode("/flujo/", $row->url);
}

$x = 1;
$excel->read(ABSPATH . $check[1]);

$x = 1;
while ($x <= $excel->sheets[0]['numRows']) {
    if($x > 1){
        $name = iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][3]);
        $getData = $wpdb->get_var("SELECT count(`name`) FROM `wtw_department_rel` WHERE `name` = '$name'");
        if($getData == 0 && $name != ""){
            $wpdb->insert('wtw_department_rel', array(
                'name' => $name,
                'parent_name' => 0,
                'section' => "D"
            ));
        }
    }

    $x++;
}
$x = 1;
while ($x <= $excel->sheets[0]['numRows']) {
    if($x > 1){
        $name = iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][2]);
        $parentname = iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][3]);
        $getData = $wpdb->get_var("SELECT count(`name`) FROM `wtw_department_rel` WHERE `name` = '$name'");
        if($getData == 0 && $name != ""){
            $wpdb->insert('wtw_department_rel', array(
                'name' => $name,
                'parent_name' => $parentname,
                'section' => "M"
            ));
        }
    }

    $x++;
}
$x = 1;
while ($x <= $excel->sheets[0]['numRows']) {
    if($x > 1){
        $name = iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][1]);
        $parentname = iconv('ISO-8859-1', 'UTF-8', $excel->sheets[0]['cells'][$x][2]);
        $getData = $wpdb->get_var("SELECT count(`name`) FROM `wtw_department_rel` WHERE `name` = '$name'");
        if($getData == 0 && $name != ""){
            $wpdb->insert('wtw_department_rel', array(
                'name' => $name,
                'parent_name' => $parentname,
                'section' => "B"
            ));
        }
    }

    $x++;
}