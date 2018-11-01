<?php 
$userData = get_userdata($bookingPayment->booking_from);
$trainerData = get_userdata($bookingPayment->user_id);
$terMyTerm = get_term($bookingPayment->category_id, "category");
if($bookingPayment->status == 1) {
    $getBookingPayment = $wpdb->get_results("SELECT * FROM `wtw_booking_price` WHERE `booking_id` = $bookingPayment->id");
    
    if ($getBookingPayment[0]->booking_paid == 0) {
        $stat1 = 0;
    } else {
        $stat1 = 1;
    }
    $data = 1;
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
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/swal-forms.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/sweetalert.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/toastr.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/sweetalert.js"></script>
<script src="<?php echo $plugin_url; ?>js/swal-forms.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
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
                <?php 
                if($data == 1) {
                    ?>
                    <tr>
                    <th><?php if ($language == "es_ES") {
                            echo "Estado de pago";
                        } else {
                            echo "Payment Status";
                        } ?></th>
                    <td><label class="switch" title="">
                        <input type="checkbox" name="my-checkbox" <?php if($stat1 ==  1 ) { echo "checked"; ?> disabled <?php  } ?>>
                        <span class="slider round"></span>
                        </label>
                        <span><?php if ($language == "es_ES") {
                                    echo "Una vez pagado, no puede cambiar eso.";
                                } else {
                                    echo "Once Paid You can not change that.";
                                } ?></span>
                    </td>
                    </tr>
                    <?php 
                }
                ?>
                </table>

  
</div>
</div>
<script>
jQuery("input[name='my-checkbox']").on("change", function (event, state) {
  if(this.checked) {
      jQuery(this).attr("disabled" , "disabled");
      swal({
        title: "<?php if ($language == "es_ES") {
                    echo "¿Estás seguro?";
                } else {
                    echo "Are you sure?";
                } ?>",
        text: "<?php if ($language == "es_ES") {
                    echo "Usted ha pagado entrenador para esta reserva";
                } else {
                    echo "You have paid trainer for this booking";
                } ?>",
        type: "success",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "<?php if ($language == "es_ES") {
                                echo "Sí, Pagado!";
                            } else {
                                echo "Yes, Paid!";
                            } ?>",
        closeOnConfirm: false
        },
        function(){
            jQuery.ajax({
				type: "POST",
				data: {"userID" : "<?php echo $bookingPayment->user_id; ?>" , "bookingID":"<?php echo $bookingPayment->id; ?>"},
				url:  '<?php echo site_url(); ?>/wp-content/plugins/olu app/ajax/paidStatus.php/?lang=en',
				success: function (data) {
					 swal({   title: "<?php if ($language == "es_ES") {
                            echo "Eliminado!";
                        } else {
                            echo "Deleted!";
                        } ?>",   text: "<?php if ($language == "es_ES") {
                                        echo "Actualización de estado de pago";
                                    } else {
                                        echo "Payment Status Update";
                                    } ?>",  type:  "success"},function(){location.reload();
                    });
					// setTimeout(function () { location.reload(); }, 3000);
				}
			});
       
    });
  } else {
      alert("Unchecked");
  }

});
</script>