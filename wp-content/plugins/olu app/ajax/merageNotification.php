<?php 
include('../../../../wp-config.php');
global $wpdb;
$name = $_POST['name'];
$email = $_POST['email'];
$notificationDate = date('Y-m-d H:i:s');

$wpdb->insert( 'im_notification', array(
		'title' => $name,
		'description' => $email,
		'notificationDate' => $notificationDate )
		);

 ?>
 <table class="table table-striped view-details">
<tbody>
<tr>
<th>S.no</th>
<th>Title</th>
<th>Description</th>
<th>Date</th>
</tr>
<?php 
$i=1;
foreach( $wpdb->get_results("SELECT * FROM `im_notification` order by `id` DESC") as $key => $row)
	{
	?>
<tr>
<td><?php echo $i; ?></th>
<td><input type="text" placeholder="" class="form-control " id="name" name="title" disabled value="<?php echo $row->title; ?>"/></td>
<td><textarea disabled><?php echo $row->description; ?></textarea></td>
<td><input type="text" placeholder="" class="form-control " id="name" name="title" disabled value="<?php echo $row->notificationDate; ?>"/></td>
</tr>
<?php $i++; } ?>

</tbody>
</table>
