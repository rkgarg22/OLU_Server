<?php
/*
Plugin Name: Upload Trainer
Description: This plugin is to import Records from Excel File in .xls File Structure
Author: Abhinav Grover
*/
if (is_admin() || get_current_user_id() == 16)
{   
  function form_create_trainers_section() 
	 {  
	add_menu_page("Subir entrenador", "Subir entrenador",1, "upload_trainer","");
	add_submenu_page('upload_trainer', 'Subir entrenador', 'Subir entrenador', 8, 'upload_trainer', 'upload_trainer' );
	 }  
   add_action('admin_menu', 'form_create_trainers_section'); 
   
   
   
}
function upload_trainer()
{
	
//include('../wp-config.php');
global $wpdb;
$plugin_url = plugin_dir_url( __FILE__ );
wp_enqueue_script('jquery');
// This will enqueue the Media Uploader script
wp_enqueue_media();
// And let's not forget the script we wrote earlier
wp_enqueue_script('custom.js');
?>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/style.css' type='text/css'/>
<link rel='stylesheet' href='<?php echo $plugin_url; ?>css/bootstrap.min.css' type='text/css'/>
<script src="<?php echo $plugin_url; ?>js/bootstrap.min.js"></script>
<script src="<?php echo $plugin_url; ?>js/jquery.validate.js"></script>
<script src="<?php echo $plugin_url; ?>js/form.js"></script>
<script src="<?php echo $plugin_url; ?>js/custom.js"></script>

<script>
jQuery(document).ready(function(){
jQuery('.select-option').each(function(){
	jQuery(this).click(function(){
		var data_attr = jQuery(this).data('attribute');
		window.location.href="admin.php?page=cities&req="+data_attr;	
	})
	});
	});
</script>
<div class="box-container">
<div class="bx-innr">
<input type="hidden" class="site_url" value="<?php echo site_url(); ?>">
	<h1>Subir archivo (el archivo debe ser estructura .xls) PASO 1-2</h1>
	 <!--<form role="form" id="uploadfile" action="" method="post" novalidate="novalidate" enctype="multipart/form-data">
		<table class="table table-striped view-all">
			<tr>
				<th>Choose a File</th>
				<th>Upload</th>
			</tr>
			<tr>
				<td><input type="file" class="form-control" id="file_upload" name="filee" multiple="true"></th>
				<td><input type="submit" value="Upload File" class="btn btn-primary add_city" /></td>
				<td><div class="loader_section" style="display:none;"><img src="http://swd.stagingdevsite.com/dev/wp-content/plugins/trainerUpload/images/loader.gif"/></div></td>
			</tr>
		</table>
	</form>-->
	<!-------------------------------- Code check------------------------------------>
	<input type="button" name="upload-btn" onclick="after_btn(this)" id="after-upload-btn" class="btn btn-primary after-upload-btn" value="Upload Image">
	<input type="hidden" name="img_after_url[]" id="image_url_after<?php echo $row->row_id; ?>" class="regular-text " value="<?php echo $row->after; ?>">
	
	<!-------------------------------- Code check------------------------------------>
<div class="message-section"></div>
</div>
<div class="bx-innr bx_inner_1" style="display:none;">
<input type="hidden" name="file_id" class="file_id" value="">
	<h1> PASO 2-2 </h1>
	<div class="section-table-details">
<p>Su archivo se subirá correctamente, por favor, haga clic en el botón Cargar para insertar todos los productos.</p>
<button type="button" class="upload_products">Upload File</button>
</div>
</div>
<div class="bx-innr bx_inner_2" style="display:none;">
<input type="hidden" name="file_id" class="file_id" value="">
	<h1>Subir Restaurantes</h1>
	<div class="section-table-details">
<p>Evite la página de recarga una vez que se cargan los productos</p>
<img src="<?php echo site_url(); ?>/wp-content/plugins/trainerUpload/images/progressBar.gif" class="img-responsive">
</div>
</div>
<div class="bx-innr bx_inner_3" style="display:none;">
<input type="hidden" name="file_id" class="file_id" value="">
	<h1>Carga completa</h1>
	<div class="section-table-details">
<p>ALl el contenido del archivo se ha subido correctamente</p>
</div>
</div>

<div class="works1"></div>
</div>
<script>
		function after_btn(e)
		{
		var btnClk = e;
		// var htmlstring = jQuery(this).html();
		var image = wp.media({ 
			title: 'Cargar imagen',
			// mutiple: true if you want to upload multiple files at once
			multiple: false
		}).open()
		.on('select', function(e){
			// This will return the selected image from the Media Uploader, the result is an object
			var uploaded_image = image.state().get('selection').first();
			// We convert uploaded_image to a JSON object to make accessing it easier
			// Output to the console uploaded_image
			var image_url = uploaded_image.toJSON().url;
			// Let's assign the url value to the input field
			jQuery('#image_url_after').val(image_url);
			//alert(cost);
			jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url(); ?>/wp-content/plugins/trainerUpload/ajax/submit_form.php', 
			data:{image_url:image_url,format:'raw'},
			success:function(resp){
				jQuery(".bx-innr:first").fadeOut( "slow");
				jQuery(".bx_inner_1").fadeIn( "slow");

			}
			});
			// jQuery(".img_after").attr("src", image_url);
			// jQuery(".img_after").show();
		});
		}
		</script>
<?php
}