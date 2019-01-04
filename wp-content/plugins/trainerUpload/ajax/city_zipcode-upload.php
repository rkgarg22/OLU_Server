<?php 
include('../../../../wp-config.php');
global $wpdb;
require_once(ABSPATH.'wp-content/plugins/ProductImport/inc/PHPExcel/IOFactory.php');

// $wpdb->query('TRUNCATE TABLE im_posts');
// $wpdb->query('TRUNCATE TABLE im_postmeta');
// $wpdb->query('TRUNCATE TABLE im_check_in_order');
// $wpdb->query('TRUNCATE TABLE im_deal_timmings');
// $wpdb->query('TRUNCATE TABLE im_facility');

foreach( $wpdb->get_results("SELECT * FROM `im_upload_file` ORDER BY `url_id` DESC Limit 1") as $key => $row)
{
	$check = explode(".com/",$row->url );
	
}
$document = PHPExcel_IOFactory::load(ABSPATH.$check[1]);
$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
//$singlerow=$activeSheetData[2];
//main insertion
// echo '<pre>';
// print_r($activeSheetData);
// echo '</pre>';

$i=0;
$maxiterations=159;

	foreach($activeSheetData as $singledata)
	{
		if($i < $maxiterations)
		{
			if($i>=1)
			{
				$rest_name=$singledata['A'];
				$deal= get_page_by_title($rest_name,'ARRAY_A','deals' );
				$deal_id=$deal['ID'];
				$city=$singledata['G'];
				$zip=$singledata['I'];
				update_post_meta($deal_id, 'city' , $city);
				update_post_meta($deal_id, 'zipcode', $zip);
			}
			$i++;
		}
		else
		{
			break;
		}
}


