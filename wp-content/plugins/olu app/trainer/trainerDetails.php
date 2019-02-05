
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
$userImageUrl = get_user_meta($userID, "userImageUrl", true);
$firstName = get_user_meta($userID, "first_name", true);
$lastName = get_user_meta($userID, "last_name", true);
$latitude = get_user_meta($userID, "latitude", true);
$longitude = get_user_meta($userID, "longitude", true);
$gender = get_user_meta($userID, "gender", true);
$phone = get_user_meta($userID, "phone", true);
$dob = get_user_meta($userID, "dob", true);
$isApprove = get_user_meta($userID, "isApprove", true);
$description = get_user_meta($userID, "description", true);
$isActive = get_user_meta($userID, "isActive", true);
if($isActive == "") {
    $isActive = 1;
}
$users = get_userdata($userID);
?>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/toastr.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/swal-forms.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/sweetalert.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css'/>
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
        echo "ENTRENADOR";
    } else {
        echo "ENTRENADOR";
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
                        echo "NOMBRE";
                    } else {
                        echo "First Name";
                    } ?></th>
                <td><?php echo $firstName; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Apellido";
                    } else {
                        echo "Last Name";
                    } ?></th>
                <td><?php echo $lastName; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Email";
                    } else {
                        echo "Email";
                    } ?></th>
                <td><?php echo $users->data->user_login; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Latitude";
                    } else {
                        echo "Latitude";
                    } ?></th>
                <td><?php echo $latitude; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Longitude";
                    } else {
                        echo "Longitude";
                    } ?></th>
                <td><?php echo $longitude; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Género";
                    } else {
                        echo "Gender";
                    } ?></th>
                <td><?php echo $gender; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Teléfono";
                    } else {
                        echo "Phone";
                    } ?></th>
                <td><?php echo $phone; ?></td>
                </tr>
                <tr>
                <th style="width:50%;"><?php if ($language == "es_ES") {
                        echo "Descripción";
                    } else {
                        echo "Description";
                    } ?></th>
                <td><?php echo $description; ?></td>
                </tr>
                
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Fecha de nacimiento";
                    } else {
                        echo "Date Of Birth";
                    } ?></th>
                <td><?php echo $dob; ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Imagen";
                    } else {
                        echo "Image";
                    } ?></th>
                <td> <a href="<?php echo $userImageUrl ?>" target="blank"><?php if ($language == "es_ES") {
                                                                                echo "Ver Imagen";
                                                                            } else {
                                                                                echo "View Image";
                                                                            } ?></a></td>
                </tr>
                    <tr>
                    <th><?php if ($language == "es_ES") {
                            echo "ESTADO";
                        } else {
                            echo "ESTADO";
                        } ?></th>
                    <td><label class="switch" title="">
                        <input type="checkbox" name="is-active" <?php if ($isActive == 1) {
                                                                        echo "checked"; ?>  <?php 
                                                                                                                    } ?>>
                        <span class="slider round"></span>
                        </label>
                        
                    </td>
                    </tr>
                    <tr>
                    <th><?php if ($language == "es_ES") {
                            echo "Historial de pagos";
                        } else {
                            echo "Historial de pagos";
                        } ?></th>
                    <td>
                        
                        <a href="<?php echo site_url(); ?>/api/bookingHistory/export.php/?userID=<?php echo $userID; ?>&status=1&lang=es" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a>
                    </td>
                   <!--  </tr>
                    <tr>
                    <th><?php if ($language == "es_ES") {
                            echo "Historial de pagos de exportación (Sin pagar)";
                        } else {
                            echo "Export Payment History (Un Paid)";
                        } ?></th>
                    <td>
                        <a href="<?php echo site_url(); ?>/api/payment/paymentHistory/export.php/?userID=<?php echo $userID; ?>&order=DESC&isPaid=0&lang=es" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a>
                    </td>
                    </tr>
                    <tr>
                    <th><?php if ($language == "es_ES") {
                            echo "Entrenadores realizaron lecciones de historia.";
                        } else {
                            echo "Trainers performed lessons history ";
                        } ?></th>
                    <td>
                        <a href="<?php echo site_url(); ?>/api/bookingHistory/export.php/?userID=<?php echo $userID; ?>&status=1&lang=es" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a>
                    </td>
                    </tr> -->
                    <tr>
                    <th><?php if ($language == "es_ES") {
                            echo "Editar";
                        } else {
                            echo "Edit";
                        } ?></th>
                    <td><a href="<?php echo site_url(); ?>/wp-admin/user-edit.php?user_id=<?php echo $userID; ?>" class="btn btn-info"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                    </td>
                    </tr>
                </table>

                <div class="categoiryData">
                <h3><?php if ($language == "es_ES") {
                        echo "Categorías";
                    } else {
                        echo "Categories";
                    } ?></h3>
                    <table class="table table-striped">
                        <tr>
                            <th><?php if ($language == "es_ES") {
                                    echo "nombre de la categoría";
                                } else {
                                    echo "Category Name";
                                } ?></th>
                            <th><?php if ($language == "es_ES") {
                                    echo "Precio único";
                                } else {
                                    echo "Single Price";
                                } ?></th>
                            <th><?php if ($language == "es_ES") {
                                    echo "Precio grupal";
                                } else {
                                    echo "Group Price";
                                } ?>
                            </th>
                            <th><?php if ($language == "es_ES") {
                                    echo "Precio grupal para 3";
                                } else {
                                    echo "Group Price For 3";
                                } ?>
                            </th>
                            <th><?php if ($language == "es_ES") {
                                    echo "Precio grupal para 4";
                                } else {
                                    echo "Group Price For 4";
                                } ?>
                            </th>
                            <th><?php if ($language == "es_ES") {
                                    echo "Precio de la empresa";
                                } else {
                                    echo "Company Price";
                                } ?>
                            </th>
                        </tr>
                        <?php 
                        foreach ($data as $key => $value) {
                            $terMyTerm = get_term($value->category_id, "category");
                            ?>
                               <tr>
                                    <td><?php echo $terMyTerm->name ?></td>
                                    <td><?php echo $value->single_price; ?></td>
                                    <td><?php echo $value->group_price; ?></td>
                                    <td><?php echo $value->group_price3; ?></td>
                                    <td><?php echo $value->group_price4; ?></td>
                                    <td><?php echo $value->company_price; ?></td>
                               </tr>
                               <?php

                            }
                            ?>
                    </table>
                    <?php 
                        if($isApprove != "yes") {
                            ?>
                            <div class="col-lg-6"><button class="btn btn-success" onclick="approveMyTrainer(<?php echo $userID; ?>)">Accept</button></div>
                            <?php
                        }
                    ?>
                   
                </div>

  
</div>
</div>

<script>
jQuery("input[name='is-active']").on("change", function (event, state) {
  if(this.checked) {
      var checked = 1;
  } else {
      var checked = 0;
  }
      swal({
        title: "<?php if ($language == "es_ES") {
                    echo "¿Estás seguro?";
                } else {
                    echo "Are you sure?";
                } ?>",
        text: "<?php if ($language == "es_ES") {
                    echo "You Want to disable the user";
                } else {
                    echo "Quieres deshabilitar al usuario";
                } ?>",
        type: "success",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "<?php if ($language == "es_ES") {
                                echo "Sí!";
                            } else {
                                echo "Yes!";
                            } ?>",
        },
        function(isConfirm){
            jQuery.ajax({
				type: "POST",
				data: {"userID" : "<?php echo $userID; ?>" , "checked":checked},
				url:  '<?php echo site_url(); ?>/wp-content/plugins/olu app/ajax/isActive.php/?lang=en',
				success: function (data) {
					 swal({   title: "<?php if ($language == "es_ES") {
                            echo "Hecho!";
                        } else {
                            echo "Done!";
                        } ?>",   text: "<?php if ($language == "es_ES") {
                                            echo "Tarea terminada";
                                        } else {
                                            echo "Task Done";
                                        } ?>",  type:  "success"},function(){location.reload();
                    });
					// setTimeout(function () { location.reload(); }, 3000);
				}
			});
       
    });
  

});
</script>