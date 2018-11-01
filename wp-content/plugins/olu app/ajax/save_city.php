<?php 
include('../../../../wp-config.php');
global $wpdb;
$city = $_POST['city'];
	$countt = $wpdb->get_var("SELECT count(`name`) FROM `northside_getstates` where `name`='$city'");
	if($countt == 0)
	{
		$wpdb->insert( 'northside_getstates', array(
		'name' => $city,
		'value' => $city )
		);
		?>
		<table class="table table-striped view-details">
		<tbody>
		<tr>
		<th>S.no</th>
		<th>City Name</th>
		<th>Edit</th>
		<th>Delete</th>
		</tr>
		<?php
		$i=1;
		foreach( $wpdb->get_results("SELECT * FROM `northside_getstates`") as $key => $row)
		{
		?>
		<tr>
		<td><?php echo $i; ?></th>
		<td><input type="text" placeholder="Enter City" class="form-control edit_city<?php echo $i; ?>"" id="name" name="city" value="<?php echo $row->name; ?>"/></td>
		<td><input type="Button" onclick="edit_row('edit_city<?php echo $i; ?>/<?php echo $row->id; ?>')" class="btn btn-primary edit_city" value="Edit City" id="Edit_city" name="Edit_city" data-attribute="<?php echo $row->name; ?>"/></td>
		<td><input type="Button" id="Delete_city" value="Delete City" class="btn btn-danger delete_city" onclick="delete_row('<?php echo $row->name; ?>')" data-attribute="<?php echo $row->name; ?>"/></td>
		<td><div class="loader_section" style="display:none;"></div></td>
		</tr>
		<?php
		$i++; }
		?></tbody>
		</table><?php
	}
	else
	{
		echo "1";
		die();
	}
		