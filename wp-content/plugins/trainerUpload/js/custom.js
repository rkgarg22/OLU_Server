var link = jQuery(".site_url").val();

/*************Order cancelation form***************/

jQuery(function($) {

	jQuery('#uploadfile').validate({

		submitHandler: function(form) {

			// var image='<img src="'+link+'"wp-content/plugins/trainerUpload/images/loader.gif"/>';

			// jQuery('.loader_section').empty().append(image);

			jQuery('.loader_section').show();

				var filee = jQuery('#file_upload').val();

			 jQuery(form).ajaxSubmit({

				type: "POST",

				url: link+'wp-content/plugins/trainerUpload/ajax/submit_form.php', 

				data:{filee:filee,format:'raw'},

				success: function(data) 

				{

					jQuery('.loader_section').hide();

					if(data == "2A")

					{

						jQuery(".message-section").empty().append('<div class="alert alert-warning">Please Upload the file of .xls extention only</div>');

					}

					else if(data == "3A")

					{

						jQuery(".message-section").empty().append('<div class="alert alert-warning">Please select file before submiting form</div>');

					}

					else

					{

						jQuery(".bx-innr:first").fadeOut( "slow");

						jQuery(".bx_inner_1").fadeIn( "slow");

					}

				}

			 });

		}

		

	});

});



/**************************************Upload Products*************************************/

jQuery(document).ready(function(){

	jQuery(".upload_products").click(function(){
		var link = jQuery(".site_url").val();

		jQuery(".bx_inner_1").fadeOut("slow");

		jQuery(".bx_inner_2").fadeIn("slow");

		jQuery.ajax({

			type: "POST",

			url: link +'/wp-content/plugins/trainerUpload/ajax/uploadTrainer.php', 

			data:{format:'raw'},

			success:function(resp){
				jQuery.ajax({

					type: "POST",

					url: link + '/wp-content/plugins/trainerUpload/ajax/uploadTrainer.php',

					data: { format: 'raw' },

					success: function (resp) {
						jQuery(".bx_inner_2").fadeOut("slow");

						jQuery(".bx_inner_3").fadeIn("slow");

					}

				});
			}	

		});

	});

	jQuery(".upload_products1").click(function(){

		var link = jQuery(".site_url").val();
		jQuery(".bx_inner_1").fadeOut("slow");

		jQuery(".bx_inner_2").fadeIn("slow");

		jQuery.ajax({

			type: "POST",

			url: link +'/wp-content/plugins/trainerUpload/ajax/uploadTrainer.php', 

			data:{format:'raw'},

			success:function(resp){

				jQuery.ajax({

					type: "POST",

					url: link + '/wp-content/plugins/trainerUpload/ajax/uploadTrainer.php',

					data: { format: 'raw' },

					success: function (resp) {

						console.log(resp);
						jQuery(".bx_inner_2").fadeOut("slow");

						jQuery(".bx_inner_3").fadeIn("slow");

					}

				});
			}	

		});

	});

});

/**************************************Upload Products*************************************/

