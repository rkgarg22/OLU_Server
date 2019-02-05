<?php 

$userData = get_userdata($bookingPayment->booking_from);
$trainerData = get_userdata($bookingPayment->user_id);
$terMyTerm = get_term($bookingPayment->category_id, "category");
if($bookingPayment->status == 1) {
    if ($language == "es_ES") {
        $stat = "Terminado";
    } else {
        $stat = "Completed";
    }
} elseif ($bookingPayment->status == 3) {
    if ($language == "es_ES") {
        $stat = "Aceptado";
    } else {
        $stat = "Accepted";
    }
} elseif ($bookingPayment->status == 5) {
    if ($language == "es_ES") {
        $stat = "Cancelado por Entrenador";
    } else {
        $stat = "Canceled By Trainer";
    }
} elseif ($bookingPayment->status == 7) {
    if ($language == "es_ES") {
        $stat = "Cancelado por usuário";
    } else {
        $stat = "Canceled By User";
    }
} elseif ($bookingPayment->status == 2) {
    if ($language == "es_ES") {
        $stat = "Disminución";
    } else {
        $stat = "Decline";
    }
} elseif ($bookingPayment->status == 0) {
    if ($language == "es_ES") {
        $stat = "Pendiente";
    } else {
        $stat = "Pending";
    }
} else {
    $stat = "";
}
?>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/toastr.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/toastr.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>

<div class="box-container">
<div class="bx-innr">
	<h3><?php if ($language == "es_ES") {
        echo "Detalles de reserva";
    } else {
        echo "Booking Details";
    } ?></h3>
</div>

<div class="bx-innr-usr">
	
	<div class="section-table-details">
    <input type="hidden" class="suteUrl" value="<?php echo site_url(); ?>">
        <?php 
        ?>
                <table class="table table-striped">
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Correo electrónico del entrenador";
                    } else {
                        echo "Trainer Email";
                    } ?></th>
                <td><?php echo $trainerData->data->user_email; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Correo electrónico del usuario";
                    } else {
                        echo "User Email";
                    } ?></th>
                <td><?php echo $userData->data->user_email; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Categoría";
                    } else {
                        echo "Category";
                    } ?></th>
                <td><?php echo $terMyTerm->name; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Fecha para registrarse";
                    } else {
                        echo "Booking Date";
                    } ?></th>
                <td><?php echo $bookingPayment->booking_date." ".$bookingPayment->booking_start; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Reserva creada";
                    } else {
                        echo "Booking Created";
                    } ?></th>
                <td><?php echo $bookingPayment->booking_created; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Reservas para";
                    } else {
                        echo "Booking For";
                    } ?></th>
                <td><?php echo $bookingPayment->booking_for; ?></td>
                </tr>
                
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Ubicación de reserva";
                    } else {
                        echo "Booking Location";
                    } ?></th>
                <td><?php echo $bookingPayment->booking_latitude . " , " . $bookingPayment->booking_longitude; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Dirección de reserva";
                    } else {
                        echo "Booking Address";
                    } ?></th>
                <td><?php echo $bookingPayment->booking_address; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Código promocional";
                    } else {
                        echo "Promocode";
                    } ?></th>
                <td><?php echo $bookingPayment->promocode; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Estado";
                    } else {
                        echo "Status";
                    } ?></th>
                <td><?php echo $stat; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Reseñas de reservas";
                    } else {
                        echo "Booking Reviews";
                    } ?></th>
                <td><?php echo $bookingPayment1->result->review; ?></td>
                </tr>
                </table>

  
</div>
</div>