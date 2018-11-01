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

<!-- Font Awesome Icon Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.checked {
    color: orange;
}
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
        echo "Comentarios de reserva";
    } else {
        echo "Booking Reviews";
    } ?></h3>
</div>

<div class="bx-innr-usr">
	
	<div class="section-table-details">
    <input type="hidden" class="suteUrl" value="<?php echo site_url(); ?>">
        <?php 
        ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>Booking From</th>
                        <th>Booking Comment</th>
                        <th>Booking Rating</th>
                        <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        foreach ($bookingSource as $key => $value) {
                            $userData = get_userdata($value->review_from);
                            ?>
                            <tr>
                                <td><?php echo $userData->data->user_email; ?></td>
                                <td><?php echo $value->comments; ?></td>
                                <td>
                                    <div>
                                    <?php 
                                        for ($i=1; $i <= 5 ; $i++) { 
                                            ?>
                                            <span class="fa fa-star <?php if($i <= $value->rating ) { echo "checked"; } ?>"></span>
                                            <?php
                                        }
                                    ?>
                                    </div>
                                </td>
                                <td><a onclick="deleteMyRating(<?php echo $value->id; ?>)" class="btn btn-danger"><?php if ($language == "es_ES") {
                                    echo "Delete";
                                } else {
                                    echo "Borar";
                                } ?></a></td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>

  
</div>
</div>
<script>
function deleteMyRating(id) {
    swal({
        title: "<?php if ($language == "es_ES") {
                    echo "¿Estás seguro?";
                } else {
                    echo "Are you sure?";
                } ?>",
        text: "<?php if ($language == "es_ES") {
                    echo "Esto afectará las calificaciones de los usuarios.";
                } else {
                    echo "This will effect the user ratings";
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
				data: {"reviewID" : id },
				url:  '<?php echo site_url(); ?>/wp-content/plugins/olu app/ajax/deleteReview.php/?lang=en',
				success: function (data) {
					 swal({   title: "<?php if ($language == "es_ES") {
                            echo "Eliminado!";
                        } else {
                            echo "Deleted!";
                        } ?>",   text: "<?php if ($language == "es_ES") {
                                            echo "Revisión eliminada con éxito";
                                        } else {
                                            echo "Review Successfully Deleted";
                                        } ?>",  type:  "success"},function(){location.reload();
                    });
					// setTimeout(function () { location.reload(); }, 3000);
				}
			});
       
    });
}
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