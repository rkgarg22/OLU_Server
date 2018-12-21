
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
	<h2><?php if ($language == "es_ES") {
        echo "Monedero de los usuarios";
    } else {
        echo "Users Wallet";
    } ?></h2>
    <h3>Wallet Amount: $<?php echo getUserWallet($userID); ?>.000</h3>
</div>

<div class="bx-innr-usr">
	<h2><?php if ($language == "es_ES") {
        echo "historial de pagos";
    } else {
        echo "Payment History";
    } ?></h2>
	<div class="section-table-details">
<table class="table table-striped view-details" id="myTables">
<thead>
<tr>
<th><?php if ($language == "es_ES") {
        echo "Fecha";
    } else {
        echo "Date";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "nombre de la categoría";
    } else {
        echo "Category Name";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Cantidad";
    } else {
        echo "Amount";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Reference Number";
    } else {
        echo "Reference Number";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Payment Status";
    } else {
        echo "Estado de pagos";
    } ?></th>
</tr>
</thead>
<tbody>
<?php 
$i = 1;
foreach ($bookingPayment->result as $key => $row) {
   if($row->paymentStatus == 0) {
        if ($language == "es_ES") {
            $status = "REJECTED";
        } else {
            $status = "RECHAZADO";
        }
   } elseif($row->paymentStatus == 1) {
        if ($language == "es_ES") {
            $status = "APPROVED";
        } else {
            $status = "APROBADO";
        }
   } elseif($row->paymentStatus == 2) {
        if ($language == "es_ES") {
            $status = "PENIDNG";
        } else {
            $status = "PENDIENTE";
        }
   }
    ?>
<tr>
<td><?php echo $row->date; ?></td>
<td><?php echo $row->categoryName; ?></td>
<td>$<?php echo $row->amount; ?></td>
<td><?php echo $row->reference; ?></td>
<td><?php echo $status; ?></td>

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