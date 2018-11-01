<?php
include("../../../../wp-config.php");
global $wpdb;
$name = $_POST['promo_code'];
$discount_price = $_POST['discount_price'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$getCount = $wpdb->get_var("SELECT count(`name`) FROM `wtw_promocode`  WHERE `name` = '$name'");
if($getCount > 0) {
    echo "Promo Code already Exist";
} else {
    $wpdb->insert( 'wtw_promocode',
        array(
            'name' => $name,
            'start_data' => date("Y-m-d H:i:s" , strtotime($start_date)),
            'end_date' => date("Y-m-d H:i:s" , strtotime($end_date)),
            'discount' => $discount_price,
            'status' => 1)
        );
}
?>