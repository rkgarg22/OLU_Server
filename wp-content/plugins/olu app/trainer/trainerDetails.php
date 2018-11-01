
<?php 

$userImageUrl = get_user_meta($userID, "userImageUrl", true);
$firstName = get_user_meta($userID, "first_name", true);
$lastName = get_user_meta($userID, "last_name", true);
$latitude = get_user_meta($userID, "latitude", true);
$longitude = get_user_meta($userID, "longitude", true);
$gender = get_user_meta($userID, "gender", true);
$phone = get_user_meta($userID, "phone", true);
$dob = get_user_meta($userID, "dob", true);
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
        echo "de usuario";
    } else {
        echo "User Update";
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
                        echo "nombre de pila";
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
                   
                </div>

  
</div>
</div>