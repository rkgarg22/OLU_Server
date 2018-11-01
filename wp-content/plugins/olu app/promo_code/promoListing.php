
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/toastr.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/jquery-ui.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/swal-forms.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/sweetalert.css' type='text/css'/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/toastr.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery-ui.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="<?php echo $plugin_url; ?>js/sweetalert.js"></script>
<script src="<?php echo $plugin_url; ?>js/swal-forms.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>

<style>
#id {
    display:none;
}
</style>
<div class="box-container">
<div class="bx-innr">
	<h1><?php if ($language == "es_ES") {
        echo "Añadir código promocional";
    } else {
        echo "Add Promo Code";
    } ?></h1>
	<form role="form" id="addcity" action="" method="post">
    <input type="hidden" class="suteUrl" value="<?php echo site_url(); ?>">
<table class="table table-striped view-all">
<tr>
    <th><?php if ($language == "es_ES") {
            echo "Código promocional";
        } else {
            echo "Promo Code";
        } ?></th>
    <td><input type="text" placeholder="" class="form-control" id="promo_code" name="promo_code" style="text-transform:uppercase"/></td>
    </tr>
    <tr>
    <th><?php if ($language == "es_ES") {
            echo "Precio de descuento";
        } else {
            echo "Discount Price";
        } ?></th>
    <td><input type="text" placeholder="" class="form-control" id="discount_price" name="discount_price"  style="text-transform:uppercase"/></td>
    </tr>
    <tr>
    <th><?php if ($language == "es_ES") {
            echo "Fecha de inicio";
        } else {
            echo "Start Date";
        } ?></th>
    <td><input type="text" placeholder="" class="form-control" id="start_date" name="start_date"  style="text-transform:uppercase"/></td>
    </tr>
    <!-- <tr>
    <th>End Date</th>
    <td><input type="text" placeholder="Enter End Date" class="form-control" id="end_date" name="end_date"  style="text-transform:uppercase"/></td>
    </tr> -->
    <tr colspan="2">
    <td><input type="submit" value="<?php if ($language == "es_ES") {
                                        echo "Guardar código promocional";
                                    } else {
                                        echo "Save Promo Code";
                                    } ?>" class="btn btn-primary add_city" /></td>
    </tr>
    <tr>
    <td><div class="loader_section" style="display:none;"></div></td>

</tr>
</table>
</form>
<div class="message-section" style="display:none;"></div>
</div>
<div class="bx-innr">
	<h1><?php if ($language == "es_ES") {
        echo "Lista de código promocional";
    } else {
        echo "List of Promo Code";
    } ?></h1>
	<div class="section-table-details">
<table class="table table-striped view-details">
<tbody>
<tr>
<th>S.no</th>
<th><?php if ($language == "es_ES") {
        echo "Código";
    } else {
        echo "Code";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Fecha de inicio";
    } else {
        echo "Start Date";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Precio de descuento";
    } else {
        echo "Discount Price";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Editar";
    } else {
        echo "Edit";
    } ?></th>
<th><?php if ($language == "es_ES") {
        echo "Borrar";
    } else {
        echo "Delete";
    } ?></th>
</tr>
<?php 
$i = 1;
foreach ($wpdb->get_results("SELECT * FROM `wtw_promocode` ORDER BY `id` DESC") as $key => $row) {
    ?>
<tr>
<td><?php echo $i; ?></th>
<td><?php echo $row->name; ?></th>
<td><?php echo date("Y-m-d" , strtotime($row->start_data)); ?></th>
<td><?php echo $row->discount; ?></th>
<td><button class="btn btn-warning" onclick="edit_form('<?php echo $row->id ?>' , '<?php echo $row->name; ?>' , '<?php echo $row->discount; ?>');"><i class="far fa-edit"></i></button></th>
<td><button class="btn btn-danger" onclick='deleteMyPromoCode(<?php echo $row->id; ?>);'><i class="far fa-trash-alt" ></i></button></th>
</tr>
<?php $i++;
} ?>
</tbody>
</tbody>
</table>
</div>
</div>
<script>
 jQuery(function() {
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
  });
  jQuery("#start_date").datepicker({ minDate: 0 });
  jQuery("#end_date").datepicker({ minDate: 0 });

function deleteMyPromoCode(promoCode) {
    swal({
        title: "<?php if ($language == "es_ES") {
                    echo "¿Estás seguro?";
                } else {
                    echo "Are you sure?";
                } ?>",
        text: "<?php if ($language == "es_ES") {
                    echo "¡Tu no podrá recuperar este código promocional!";
                } else {
                    echo "Your will not be able to recover this Promocode!";
                } ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php if ($language == "es_ES") {
                                echo "Sí, eliminarlo!";
                            } else {
                                echo "Yes, delete it!";
                            } ?>",
        closeOnConfirm: false
        },
        function(){
            jQuery.ajax({
				type: "POST",
				data: {promoCode:promoCode},
				url:  '<?php echo site_url(); ?>/wp-content/plugins/olu app/ajax/deletePromoCode.php/?lang=en',
				success: function (data) {
					 swal({   title: "<?php if ($language == "es_ES") {
                echo "Eliminado!";
                    } else {
                        echo "Deleted!";
                    } ?>",   text: "<?php if ($language == "es_ES") {
                    echo "Su código promocional ha sido eliminado.";
                } else {
                    echo "Your Promocode has been deleted.";
                } ?>",  type:  "success"},function(){location.reload();
                    });
					// setTimeout(function () { location.reload(); }, 3000);
				}
			});
       
    });
  }

  function edit_form(id, name , discount) {
      swal.withForm({
    title: '<?php if ($language == "es_ES") {
                echo "editar promocode";
            } else {
                echo "Edit Promocode";
            } ?>',
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
  showLoaderOnConfirm: true,
            closeOnConfirm: false,
    confirmButtonText: '<?php if ($language == "es_ES") {
                            echo "Actualizar";
                        } else {
                            echo "Update";
                        } ?>',
    // closeOnConfirm: true,
    formFields: [
      { id: 'name', placeholder: '<?php if ($language == "es_ES") {
                                        echo "Código promocional";
                                    } else {
                                        echo "Promo Code";
                                    } ?>' , value : name },
      { id: 'id', value: id },
      { id: 'discount', placeholder: '<?php if ($language == "es_ES") {
                                            echo "Precio de descuento";
                                        } else {
                                            echo "Discount Price";
                                        } ?>' , value : discount },
    ]
  }, function (isConfirm) {
    // do whatever you want with the form data
    jQuery.ajax({
				type: "POST",
				data: this.swalForm,
				url:  '<?php echo site_url(); ?>/wp-content/plugins/olu app/ajax/editPromoCode.php/?lang=en',
				success: function (data) {
					 swal({   title: "<?php if ($language == "es_ES") {
                            echo "Actualizar";
                        } else {
                            echo "Update";
                        } ?>",   text: "<?php if ($language == "es_ES") {
                                        echo "Su código promocional ha sido Actualizar.";
                                    } else {
                                        echo "Your Promocode has been updated.";
                                    } ?>",  type:  "success"},function(){location.reload();
                    });
					// setTimeout(function () { location.reload(); }, 3000);
				}
			});
  });
  }

</script>

<?php
