<?php 
$first_name = get_user_meta($dataMy[0]->user_id , "first_name" , true);
$last_name = get_user_meta($dataMy[0]->user_id , "last_name" , true);
$description = get_user_meta($dataMy[0]->user_id , "description" , true);
$gender = get_user_meta($dataMy[0]->user_id , "gender" , true);
$phone = get_user_meta($dataMy[0]->user_id , "phone" , true);
$age = get_user_meta($dataMy[0]->user_id , "age" , true);
$dob = get_user_meta($dataMy[0]->user_id , "dob" , true);
$userID = $dataMy[0]->user_id;
$cateArray = array();
$getBookingPayment = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $userID");
foreach ($getBookingPayment as $key => $value) {
    $catID= $value->category_id;
    $terMyTerm = get_term($catID, "category");
    $cateArray[] = $terMyTerm->name;
}
?>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/toastr.css' type='text/css'/>
<link rel='stylesheet' href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' type='text/css'/>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css'/>
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/toastr.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>

<div class="box-container">
<div class="bx-innr">
	<h3><?php if ($language == "es_ES") {
        echo "Actualización de usuario";
    } else {
        echo "User Update Request";
    } ?></h3>
</div>

<div class="bx-innr-usr">
	
	<div class="section-table-details">
    <input type="hidden" class="suteUrl" value="<?php echo site_url(); ?>">
        <?php 
            if(empty($dataMy)) {
                ?>
                <h3><?php if ($language == "es_ES") {
                        echo "Sin la última solicitud";
                    } else {
                        echo "No Latest Request";
                    } ?></h3>
                <?php
            } elseif($dataMy[0]->status == 0) {
                ?>
                <table class="table table-striped">
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "nombre de pila";
                    } else {
                        echo "First Name";
                    } ?></th>
                <td><?php echo $dataMy[0]->first_name; ?> <?php
                if($dataMy[0]->first_name != $first_name) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php
                }
                 ?></td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Apellido";
                    } else {
                        echo "Last Name";
                    } ?></th>
                <td><?php echo $dataMy[0]->last_name; ?>
                <?php
                if ($dataMy[0]->last_name != $last_name) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php

                }
                ?>
                </td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Descripción";
                    } else {
                        echo "Description";
                    } ?></th>
                <td><?php echo $dataMy[0]->description; ?>
                <?php
                if ($dataMy[0]->description != $description) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php

                }
                ?>
                </td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Género";
                    } else {
                        echo "Gender";
                    } ?></th>
                <td><?php echo $dataMy[0]->gender; ?>
                <?php
                if ($dataMy[0]->gender != $gender) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php

                }
                ?>
                </td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Teléfono";
                    } else {
                        echo "Phone";
                    } ?></th>
                <td><?php echo $dataMy[0]->phone; ?>
                <?php
                if ($dataMy[0]->phone != $phone) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php

                }
                ?>
                </td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Años";
                    } else {
                        echo "Age";
                    } ?></th>
                <td><?php echo $dataMy[0]->age; ?>
                <?php
                if ($dataMy[0]->age != $age) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php

                }
                ?>
                </td>
                </tr>
                <tr>
                <th><?php if ($language == "es_ES") {
                        echo "Fecha de nacimiento";
                    } else {
                        echo "Date Of Birth";
                    } ?></th>
                <td><?php echo $dataMy[0]->dob; ?>
                <?php
                if ($dataMy[0]->dob != $dob) {
                    ?>
                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                    <?php

                }
                ?>
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
                            foreach (json_decode($dataMy[0]->categories) as $key => $value) {
                                $terMyTerm = get_term($value->CategryID, "category");
                               ?>
                               <tr>
                                    <td><?php echo $terMyTerm->name; ?>
                                    <?php
                                    if (!in_array($terMyTerm->name, $cateArray)) {
                                        ?>
                                        <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        <?php

                                    }
                                    ?>
                                    </td>
                                    <td><?php echo $value->singlePrice;  ?></td>
                                    <td><?php echo $value->groupPrice2; ?></td>
                                    <td><?php echo $value->groupPrice3; ?></td>
                                    <td><?php echo $value->groupPrice4; ?></td>
                                    <td><?php echo $value->companyPrice; ?></td>
                               </tr>
                               <?php
                            }
                        ?>
                    </table>
                    <div class="col-lg-6"><button class="btn btn-success" onclick="accpetMyUpdate(<?php echo $dataMy[0]->id; ?>)">Accept</button></div>
                    <div class="col-lg-6"><button class="btn btn-danger" onclick="rejectUpdate(<?php echo $dataMy[0]->id; ?>)">Decline</button></div>
                </div>
                <?php

            } else {
                {
                    ?>
                <h3><?php if ($language == "es_ES") {
                        echo "Sin la última solicitud";
                    } else {
                        echo "No Latest Request";
                    } ?></h3>
                <?php

            }
            }
        ?>

  
</div>
</div>