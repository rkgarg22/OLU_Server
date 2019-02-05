<?php 

?>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css'/>
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>

<div class="box-container">
<div class="bx-innr">
	<h3><?php if($language == "es_ES") { echo "Listado de usuarios"; } else { echo "Users Section"; } ?></h3>
    <a href="<?php echo site_url(); ?>/api/userListing/export1.php?lang=es" class="btn-info btn"><i class="fa fa-download" aria-hidden="true"></i><?php if ($language == "es_ES") {
                                                                                                                                                    echo " EXPORTAR REPORTE DE USUARIO";
                                                                                                                                                } else {
                                                                                                                                                    echo " EXPORTAR REPORTE DE USUARIO";
                                                                                                                                                } ?></a>
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
        echo "E-mail";
    } else {
        echo "E-mail";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Fecha de ingreso";
    } else {
        echo "Fecha de ingreso";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Reservas";
    } else {
        echo "Reservas";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Pagos";
    } else {
        echo "Pagos";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Detalles";
    } else {
        echo "Detalles";
    } ?></th>
    <th><?php if ($language == "es_ES") {
        echo "Reviews";
    } else {
        echo "Reviews";
    } ?></th>
</tr>
</thead>
<tbody>
<?php 
$i = 1;
foreach ($users as $key => $row) {
    $bookingSourceq = "http://3.16.104.146/api/bookingHistory/?userID=" . $row->ID . "&status=";

    $j = 3;
    $completedData = json_decode(file_get_contents($bookingSourceq . $j));
    if (!empty($completedData->result)) {
        $cname = "btn-danger";
    } else {
        $cname = "btn-primary";
    }
    ?>
<tr>
<td><?php echo $i; ?></td>
<td><p><?php echo get_user_meta($row->ID , "first_name" , true)." ". get_user_meta($row->ID, "last_name", true) ?></p></td>
<td><p><a href="mailto:<?php echo $row->data->user_email; ?>"><?php echo $row->data->user_email; ?></a></p></td>
<td><p><?php echo $row->data->user_registered; ?></p></td>
<td> <p><a class="btn <?php echo $cname; ?>" href="/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->ID ?>&action=bookings"><?php if ($language == "es_ES") {
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
    jQuery('#myTables').DataTable({
        language: { search: "Buscar" },
    });
} );
</script>
<style>
.table thead,
.table th {text-align: center;}
</style>