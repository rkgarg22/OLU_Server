<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
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
	<h1><?php if ($language == "es_ES") {
        echo "Reserva";
    } else {
        echo "Booking";
    } ?></h1>
</div>

<div class="bx-innr-usr">
	<h1><?php if ($language == "es_ES") {
        echo "Listado de reservass";
    } else {
        echo "Booking Listing";
    } ?></h1>
	<div class="section-table-details">
        <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home"><?php if ($language == "es_ES") {
                                                            echo "Terminado";
                                                        } else {
                                                            echo "Completed";
                                                        } ?></a></li>
    <li><a data-toggle="tab" href="#menu1"><?php if ($language == "es_ES") {
                                                echo "Aceptado";
                                            } else {
                                                echo "Accepted";
                                            } ?></a></li>
    <li><a data-toggle="tab" href="#menu2"><?php if ($language == "es_ES") {
                                                echo "Cancelado";
                                            } else {
                                                echo "Cancelled";
                                            } ?></a></li>
    <li><a data-toggle="tab" href="#menu3"><?php if ($language == "es_ES") {
                                                echo "Rechazado";
                                            } else {
                                                echo "Declined";
                                            } ?></a></li>
    <li><a data-toggle="tab" href="#menu4"><?php if ($language == "es_ES") {
                                                echo "Pendiente";
                                            } else {
                                                echo "Pending";
                                            } ?>Pending</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <table class="table table-striped view-details" id="myTables">
        <thead>
        <tr>
        <th>S.no</th>
        <th><?php if ($language == "es_ES") {
                echo "Usuario reservado";
            } else {
                echo "Booked User";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Categoría";
            } else {
                echo "Category";
            } ?></th>
        <!-- <th><?php if ($language == "es_ES") {
                echo "Reserva creada";
            } else {
                echo "Booking Created";
            } ?></th> -->
        <th><?php if ($language == "es_ES") {
                echo "Fecha para registrarse";
            } else {
                echo "Booking Date";
            } ?></th>
             <th><?php if ($language == "es_ES") {
                    echo "Estado de pago";
                } else {
                    echo "Payment Status";
                } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Detalles";
            } else {
                echo "Details";
            } ?></th>
        
        </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $completedData = file_get_contents($bookingSource.$i);
       $completedData = json_decode($completedData);
        if (!empty($completedData->result)) {
        foreach ($completedData->result as $key => $row) {
            $getBookingPayment = $wpdb->get_results("SELECT * FROM `wtw_booking_price` WHERE `booking_id` = $row->bookingID");
               
            $user_info = get_userdata($row->userID);
            
            $terMyTerm = get_term($row->categoryID, "category");
            if ( !empty($getBookingPayment) && $getBookingPayment[0]->booking_paid == 0) {
                $className = "danger";
                if ($language == "es_ES") {
                    $booking_status = "No pagado";
                } else {
                    $booking_status = "Not Paid";
                }
            } else {
                $className = "success";
                if ($language == "es_ES") {
                    $booking_status = "Pagado";
                } else {
                    $booking_status = "Paid";
                }
            }
            ?>
        <tr>
        <td><?php echo $i; ?></td>
        <td><?php if (empty($user_info)) {
                if ($language == "es_ES") {
                    echo "Usuario eliminado";
                } else {
                    echo "User Deleted";
                }
            } else {
                echo $user_info->user_login;
            }  ?></td>
        <td><?php echo apply_filters('translate_text', $terMyTerm->name, $lang = $lang, $flags = 0); ?></td>
        <td><?php echo $row->bookingDate; ?></td>
        <td><div class="alert alert-<?php echo $className; ?>"><strong><?php echo $booking_status; ?></strong></div></td>
        <td><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=trainer&user_id=<?php echo $row->userID; ?>&booking_id=<?php echo $row->bookingID; ?>&action=bookingDetails"><?php if ($language == "es_ES") { echo "Ver";  } else { echo "View"; } ?></a></td>
        </tr>
        <?php $i++;
    }
} ?>

        </tbody>
        </table>
    </div>
    <div id="menu1" class="tab-pane fade">
      <table class="table table-striped view-details" id="myTables1">
        <thead>
        <tr>
        <th>S.no</th>
        <th><?php if ($language == "es_ES") {
                echo "Usuario reservado";
            } else {
                echo "Booked User";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Categoría";
            } else {
                echo "Category";
            } ?></th>
        <!-- <th><?php if ($language == "es_ES") {
                    echo "Reserva creada";
                } else {
                    echo "Booking Created";
                } ?></th> -->
        <th><?php if ($language == "es_ES") {
                echo "Fecha para registrarse";
            } else {
                echo "Booking Date";
            } ?></th>
            
        <th><?php if ($language == "es_ES") {
                echo "Detalles";
            } else {
                echo "Details";
            } ?></th>
        
        </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $j = 3;
        $completedData = file_get_contents($bookingSource . $j);
        $completedData = json_decode($completedData);
        if(!empty($completedData->result)) {
        foreach ($completedData->result as $key => $row) {
            $user_info = get_userdata($row->userID);
            $terMyTerm = get_term($row->categoryID, "category");
           
            ?>
        <tr>
        <td><?php echo $i; ?></th>
        <td><?php if (empty($user_info)) {
                if ($language == "es_ES") {
                    echo "Usuario eliminado";
                } else {
                    echo "User Deleted";
                }
            } else {
                echo $user_info->user_login;
            } ?></td>
        <td><?php echo apply_filters('translate_text', $terMyTerm->name, $lang = $lang, $flags = 0); ?></td>
        <td><?php echo $row->bookingDate; ?></td>
        <td><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->userID; ?>&booking_id=<?php echo $row->bookingID; ?>&action=bookingDetails"><?php if ($language == "es_ES") { echo "Ver"; } else { echo "View"; } ?></a></td>
        </tr>
        <?php $i++;
    }
} ?>

        </tbody>
        </table>
    </div>
    <div id="menu2" class="tab-pane fade">
      <table class="table table-striped view-details" id="myTables2">
        <thead>
        <tr>
        <th>S.no</th>
        <th><?php if ($language == "es_ES") {
                echo "Usuario reservado";
            } else {
                echo "Booked User";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Categoría";
            } else {
                echo "Category";
            } ?></th>
        <!-- <th><?php if ($language == "es_ES") {
                    echo "Reserva creada";
                } else {
                    echo "Booking Created";
                } ?></th> -->
        <th><?php if ($language == "es_ES") {
                echo "Fecha para registrarse";
            } else {
                echo "Booking Date";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Detalles";
            } else {
                echo "Details";
            } ?></th>
        
        </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $j = 5;
        $completedData = file_get_contents($bookingSource . $j);
        $completedData = json_decode($completedData);
        if (!empty($completedData->result)) {
        foreach ($completedData->result as $key => $row) {
            $user_info = get_userdata($row->userID);
            $terMyTerm = get_term($row->categoryID, "category");
            ?>
        <tr>
       <td><?php echo $i; ?></td>
        <td><?php if (empty($user_info)) {
                if ($language == "es_ES") {
                    echo "Usuario eliminado";
                } else {
                    echo "User Deleted";
                }
            } else {
                echo $user_info->user_login;
            } ?></td>
        <td><?php echo apply_filters('translate_text', $terMyTerm->name, $lang = $lang, $flags = 0); ?></td>
        <td><?php echo $row->bookingDate; ?></td>
        <td><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->userID; ?>&booking_id=<?php echo $row->bookingID; ?>&action=bookingDetails"><?php if ($language == "es_ES") {  echo "Ver";  } else { echo "View";  } ?></a></td>
        </tr>
        <?php $i++;
    }
} ?>
        </tbody>
        </table>
    </div>
    <div id="menu3" class="tab-pane fade">
     <table class="table table-striped view-details" id="myTables3">
        <thead>
        <tr>
        <th>S.no</th>
        <th><?php if ($language == "es_ES") {
                echo "Usuario reservado";
            } else {
                echo "Booked User";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Categoría";
            } else {
                echo "Category";
            } ?></th>
        <!-- <th><?php if ($language == "es_ES") {
                    echo "Reserva creada";
                } else {
                    echo "Booking Created";
                } ?></th> -->
        <th><?php if ($language == "es_ES") {
                echo "Fecha para registrarse";
            } else {
                echo "Booking Date";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Detalles";
            } else {
                echo "Details";
            } ?></th>
        
        </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $j = 2;
        $completedData = file_get_contents($bookingSource . $j);
        $completedData = json_decode($completedData);
        if (!empty($completedData->result)) {
        foreach ($completedData->result as $key => $row) {
            $user_info = get_userdata($row->userID);
            $terMyTerm = get_term($row->categoryID, "category");
            ?>
        <tr>
        <td><?php echo $i; ?></td>
        <td><?php if (empty($user_info)) {
                if ($language == "es_ES") {
                    echo "Usuario eliminado";
                } else {
                    echo "User Deleted";
                }
            } else {
                echo $user_info->user_login;
            } ?></td>
        <td><?php echo apply_filters('translate_text', $terMyTerm->name, $lang = $lang, $flags = 0); ?></td>
        <td><?php echo $row->bookingDate; ?></td>
        <td><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->userID; ?>&booking_id=<?php echo $row->bookingID; ?>&action=bookingDetails"><?php if ($language == "es_ES") {  echo "Ver"; } else {  echo "View";  } ?></a></td>
        </tr>
        <?php $i++;
    } } ?>
        </tbody>
        </table>
    </div>
    <div id="menu4" class="tab-pane fade">
     <table class="table table-striped view-details" id="myTables4">
        <thead>
        <tr>
        <th>S.no</th>
        <th><?php if ($language == "es_ES") {
                echo "Usuario reservado";
            } else {
                echo "Booked User";
            } ?></th>
        <th><?php if ($language == "es_ES") {
                echo "Categoría";
            } else {
                echo "Category";
            } ?></th>
        <!-- <th><?php if ($language == "es_ES") {
                    echo "Reserva creada";
                } else {
                    echo "Booking Created";
                } ?></th> -->
        <th><?php if ($language == "es_ES") {
                echo "Fecha para registrarse";
            } else {
                echo "Booking Date";
            } ?></th>
       
        <th><?php if ($language == "es_ES") {
                echo "Detalles";
            } else {
                echo "Details";
            } ?></th>
        
        </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        $j = 0;
        $completedData = file_get_contents($bookingSource . $j);
        $completedData = json_decode($completedData);
        foreach ($completedData->result as $key => $row) {
         
            $user_info = get_userdata($row->userID);
            $terMyTerm = get_term($row->categoryID, "category");
            ?>
        <tr>
       <td><?php echo $i; ?></td>
        <td><?php if (empty($user_info)) {
                if ($language == "es_ES") {
                    echo "Usuario eliminado";
                } else {
                    echo "User Deleted";
                }
            } else {
                echo $user_info->user_login;
            } ?></td>
        <td><?php echo apply_filters('translate_text', $terMyTerm->name, $lang = $lang, $flags = 0); ?></td>
        <td><?php echo $row->bookingDate; ?></td>
        <td><a class="btn btn-primary" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=olu_fitness&user_id=<?php echo $row->userID; ?>&booking_id=<?php echo $row->bookingID; ?>&action=bookingDetails"><?php if ($language == "es_ES") {  echo "Ver"; } else { echo "View"; } ?></a></td>
        </tr>
        <?php $i++;
    } ?>
        </tbody>
        </table>
    </div>
  </div>

</div>
</div>
</div>
<script>
jQuery(document).ready( function () {
    jQuery('#myTables').DataTable();
    jQuery('#myTables1').DataTable();
    jQuery('#myTables2').DataTable();
    jQuery('#myTables3').DataTable();
    jQuery('#myTables4').DataTable();
} );
</script>