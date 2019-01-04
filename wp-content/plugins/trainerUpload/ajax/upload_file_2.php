<?php 

include('../../../../wp-config.php');

global $wpdb;




foreach( $wpdb->get_results("SELECT * FROM `wtw_upload_file` ORDER BY `url_id` DESC Limit 1") as $key => $row)

{
	$check = explode(".com/",$row->url );
}

$file = fopen($check[1],"r");
echo "<pre>";
print_r(fgetcsv($file));
echo "</pre>";
fclose($file);

