<?php 
function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
}

function messageCheck($user1 , $user2) {
    global $wpdb;
    $check1 = $wpdb->get_results("SELECT * FROM `wtw_conversation` WHERE `message_from` = $user1 AND `message_to` = $user2 OR `message_from` = $user2 AND `message_to` = $user1");
    return $check1;
}


///User Category Module 
add_action('show_user_profile', 'my_show_extra_profile_fields');
add_action('edit_user_profile', 'my_show_extra_profile_fields');
add_action('user_new_form', 'my_show_extra_profile_fields');

function my_show_extra_profile_fields($user)
{
    global $wpdb;
    $Users = new Users();
    $getMyMOverType = $Users->getCategoryListing("es");
    $getTEst = $wpdb->get_results("SELECT * FROM `wtw_user_pricing` WHERE `user_id` = $user->ID");
   
    ?>
    <style>
    .multi-language-field-image a {
        display: none;
    }
    </style>
    <script>
        jQuery(document).ready(function(){
            jQuery("input[name='submit']").click(function(){
                var valueName = jQuery("input[name='fields[field_5c309f6aef415][es]']").val();
                if(valueName == "" || jQuery.isNumeric(valueName) == false) {
                    jQuery("input[name='fields[field_5c309f6aef415][es]']").removeAttr("name");
                }
            });
        });                 
    </script>

	<h3>Price Module</h3>

    <div class="container-backend">
    <?php
    if (empty($getTEst)) {
        ?>
        <div class="repeated-row">
                <div class="wrapper row">
                    <div class="col-lg-2">
                        <label for="CategorID1">
                            <select name="categorySelct[]" class="form-control" id="CategorID1">
                                <?php 
                                foreach ($getMyMOverType as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value['categoryID']; ?>"><?php echo $value['name']; ?></option>
                                        <?php

                                    }
                                    ?>
                            </select>
                        </label>
                    </div>
                <div class="col-lg-2">
                    <label for="SinglePrice1">
                            <input type="text" class="form-control" name="SinglePrice[]" value="" placeholder="Single Price" id="SinglePrice1">
                    </label>  
                </div>
                <div class="col-lg-2 priceSection" >
                    <label for="groupPrice1">
                            <input type="text" class="form-control" name="groupPrice[]" value="" placeholder="Group Price" id="groupPrice1">
                    </label>
                </div>
                <div class="col-lg-2 priceSection" >
                    <label for="groupPrice1">
                            <input type="text" class="form-control" name="groupPrice3[]" value="" placeholder="Group Price" id="groupPrice1">
                    </label>
                </div>
                <div class="col-lg-2 priceSection" >
                    <label for="groupPrice1">
                            <input type="text" class="form-control" name="groupPrice4[]" value="" placeholder="Group Price" id="groupPrice1">
                    </label>
                </div>
                <div class="col-lg-2">
                    <label for="companyPrice1">
                            <input type="text" class="form-control" name="companyPrice[]" value="" placeholder="Company Price" id="companyPrice1">
                    </label>
                </div>
               
                </div>
            </div>
        <?php

    } else {
        foreach ($getTEst as $getTEstkey => $getTEstvalue) {
        ?>
        <div class="repeated-row">
                <div class="wrapper row">
                    <div class="col-lg-2">
                        <label for="CategorID1">
                            <select name="categorySelct[]" class="form-control" id="CategorID1">
                                <?php 
                                foreach ($getMyMOverType as $key => $value) {
                                    ?>
                                        <option <?php if($getTEstvalue->category_id == $value['categoryID']) { echo "selected"; } ?> value="<?php echo $value['categoryID']; ?>"><?php echo $value['name']; ?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </label>
                    </div>
                <div class="col-lg-2">
                    <label for="SinglePrice1">
                            <input type="text" class="form-control" name="SinglePrice[]" value="<?php echo $getTEstvalue->single_price; ?>" placeholder="Single Price" id="SinglePrice1">
                    </label>  
                </div>
                <div class="col-lg-2 priceSection" >
                    <label for="groupPrice1">
                            <input type="text" class="form-control" name="groupPrice[]" value="<?php echo $getTEstvalue->group_price; ?>" placeholder="Group Price" id="groupPrice1">
                    </label>
                </div>
                <div class="col-lg-2 priceSection" >
                    <label for="groupPrice1">
                            <input type="text" class="form-control" name="groupPrice3[]" value="<?php echo $getTEstvalue->group_price3; ?>" placeholder="Group Price" id="groupPrice1">
                    </label>
                </div>
                <div class="col-lg-2 priceSection" >
                    <label for="groupPrice1">
                            <input type="text" class="form-control" name="groupPrice4[]" value="<?php echo $getTEstvalue->group_price4; ?>" placeholder="Group Price" id="groupPrice1">
                    </label>
                </div>
                <div class="col-lg-2">
                    <label for="companyPrice1">
                            <input type="text" class="form-control" name="companyPrice[]" value="<?php echo $getTEstvalue->company_price; ?>" placeholder="Company Price" id="companyPrice1">
                    </label>
                </div>
                <?php 
                if($getTEstkey != 0) {
                    ?>
                    <div class="col-lg-2">
                        <button type="button" onclick="deleteMyRow(this);" class="btn btn-danger">Borrar</button>
                    </div>
                    <?php
                }
                ?>
                </div>
            </div>
        <?php 
    }
}
    ?>
        </div>
        <button class="button button-primary button-large"  type="button" class="btn-add" onclick="addRow();">Añadir</button>

        <script>
        jQuery(document).ready(function(){
            jQuery("input[type='submit']").val("Actualizar");
        })
        </script>
        <style>
        .btn-danger {
            margin-bottom: 10px !important;
                line-height: 1.1 !important;
        }
        .user-rich-editing-wrap {
            display:none;
        }
         .user-admin-color-wrap {
            display:none;
        }
         .user-comment-shortcuts-wrap {
            display:none;
        }
         .user-admin-bar-front-wrap {
            display:none;
        }
         .user-language-wrap {
            display:none;
        }
         .user-user-login-wrap {
            display:none;
        }
         .user-display-name-wrap {
            display:none;
        }
         .user-url-wrap {
            display:none;
        }
         .user-description-wrap {
            display:none;
        }
         .user-profile-picture {
            display:none;
        }
        </style>
        <script>

        jQuery(document).ready(function(){
            jQuery("select[name='role']").val("contributor");
            jQuery("h2").remove();
            jQuery("h3").remove();
        });
          function addRow() {
            var cCount = jQuery(".repeated-row").length;
            var finCount = parseInt(cCount) + 1;
            var dataInsert = "<div class='repeated-row'> <div class='wrapper row'> <div class='col-lg-2'> <label for='CategorID"+finCount+"'> <select name='categorySelct[]' class='form-control' id='CategorID"+finCount+"'> <?php foreach ($getMyMOverType as $key => $value) { ?> <option value='<?php echo $value['categoryID']; ?>'><?php echo $value['name']; ?></option> <?php } ?> </select> </label> </div><div class='col-lg-2'> <label for='SinglePrice"+finCount+"'> <input type='text' class='form-control' name='SinglePrice[]' value='' placeholder='Single Price' id='SinglePrice"+finCount+"'> </label> </div><div class='col-lg-2 priceSection' > <label for='groupPrice"+finCount+"'> <input type='text' class='form-control' name='groupPrice[]' value='' placeholder='Group Price' id='groupPrice"+finCount+"'> </label> </div><div class='col-lg-2 priceSection' > <label for='groupPrice"+finCount+"'> <input type='text' class='form-control' name='groupPrice3[]' value='' placeholder='Group Price' id='groupPrice"+finCount+"'> </label> </div><div class='col-lg-2 priceSection' > <label for='groupPrice"+finCount+"'> <input type='text' class='form-control' name='groupPrice4[]' value='' placeholder='Group Price' id='groupPrice"+finCount+"'> </label> </div><div class='col-lg-2'> <label for='companyPrice"+finCount+"'> <input type='text' class='form-control' name='companyPrice[]' value='' placeholder='Company Price' id='companyPrice"+finCount+"'> </label> </div><div class='col-lg-2'> <button type='button' onclick='deleteMyRow(this);' class='btn btn-danger'>Borrar</button> </div></div></div>";
            jQuery(dataInsert).insertAfter(".repeated-row:last");

        }

        function deleteMyRow(event) {
            jQuery(event).parent().parent().parent().remove();
        }
        </script>
<?php 
}


function save_custom_user_profile_fields($user_id)
{
    global $wpdb;
    # again do this only if you can
    if (!current_user_can('manage_options'))
        return false;
    
    # save my custom field
    $wpdb->query("DELETE  FROM `wtw_user_pricing` WHERE `user_id` = $user_id");
    foreach ($_POST['categorySelct'] as $key => $value) {
        $singlePrice = $_POST['SinglePrice'][$key];
        $groupPrice = $_POST['groupPrice'][$key];
        $groupPrice3 = $_POST['groupPrice3'][$key];
        $groupPrice4 = $_POST['groupPrice4'][$key];
        $companyPrice = $_POST['companyPrice'][$key];
        $wpdb->insert('wtw_user_pricing', array(
            'user_id' => $user_id,
            'single_price' => $singlePrice,
            'group_price' => $groupPrice,
            'group_price3' => $groupPrice3,
            'group_price4' => $groupPrice4,
            'company_price' => $companyPrice,
            'category_id' => $value
        ));
    }

    update_user_meta($user_id, "isApprove", 'yes');
    if($_POST['fields']['field_5c309f6aef415']['es'] != "") {
        $feat_image_url = wp_get_attachment_url($_POST['fields']['field_5c309f6aef415']['es']);
        update_user_meta($user_id, "userImageUrl", $feat_image_url);
    }
    update_user_meta($user_id, "description", $_POST['fields']['field_5c1ddd8ed55f0']);
}
add_action('user_register', 'save_custom_user_profile_fields');
add_action('profile_update', 'save_custom_user_profile_fields');


function admin_style()
{
    wp_enqueue_style('colorpicker', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
}
add_action('admin_enqueue_scripts', 'admin_style');
///User Category Module 
?>