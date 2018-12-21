
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>

<div class="box-container">
<div class="bx-innr">
	<h1><?php if($language == "es_ES") { echo "Sección de usuarios"; } else { echo "Users Section"; } ?></h1>
    <a href="<?php echo site_url(); ?>/wp-admin/user-new.php" class="btn-info btn"><?php if ($language == "es_ES") {  echo "Agregar usuario"; } else { echo "Add User";  } ?></a>
</div>

<div class="bx-innr-usr">
	<h1><?php if ($language == "es_ES") { echo "Listado de usuarios"; } else { echo "Users Listing"; } ?></h1>
	<div class="section-table-details">
<table class="table table-striped view-details" id="myTables">
<thead>
<tr>
<th>S.no</th>
<th><?php if ($language == "es_ES") {
        echo "Nombre";
    } else {
        echo "Name";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Listado de usuarios";
    } else {
        echo "Email";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Creado por el usuario";
    } else {
        echo "User Created";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Reservas de usuario";
    } else {
        echo "Users Bookings";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Carteras de usuario";
    } else {
        echo "Users Wallets";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Detalles de usuarios";
    } else {
        echo "Users Details";
    } ?></th>
    <th><?php if ($language == "es_ES") {
        echo "Comentarios de reserva";
    } else {
        echo "Booking Reviews";
    } ?><th>
</tr>
</thead>
<tbody>
<?php 
$i = 1;
foreach ($users as $key => $row) {
    ?>
<tr>
<td><?php echo $i; ?></th>
<td><p><?php echo get_user_meta($row->ID , "first_name" , true)." ". get_user_meta($row->ID, "last_name", true) ?></p></td>
<td><p><a href="mailto:<?php echo $row->data->user_email; ?>"><?php echo $row->data->user_email; ?></a></p></td>
<td><p><?php echo $row->data->user_registered; ?></p></td>
<td> <p><a class="btn btn-primary" href="/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->ID ?>&action=bookings"><?php if ($language == "es_ES") {
echo "Ver";
} else {
echo "View";
} ?></a></p> </td>
<td> <p><a class="btn btn-primary" href="/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->ID ?>&action=wallet"><?php if ($language == "es_ES") {
echo "Ver";
} else {
echo "View";
} ?></a></p> </td>
<td> <p><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->ID; ?>&action=profile"><?php if ($language == "es_ES") {
echo "Ver";
} else {
echo "View";
} ?></a></p> </td>
<td style="text-align:center;"> <p><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->ID; ?>&action=reviews"><?php if ($language == "es_ES") {
                                                                                                                                                                                    echo "Ver";
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo "View";
                                                                                                                                                                                } ?></a></p> </td>
</tr>
<?php $i++;
} ?>

</tbody>
</table>
</div>
</div>
</div>
<script>
jQuery(document).ready( function () {
    jQuery('#myTables').DataTable();
} );
</script>