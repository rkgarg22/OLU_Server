jQuery(function($) {
	jQuery('#contact_us_home_page').validate({
		
		rules: {
			name: {
				required: true,
				minlength: 3
			},
			email: {
				required: true
			}
		},
		submitHandler: function(form) {
			jQuery('.message-section').hide();
			var suteUrl = jQuery(".siteUrl").val();
			var image='<img src="'+suteUrl+'/wp-content/plugins/Notification Hungerpass/images/loader.gif"/>';
			jQuery('.loader').empty().append(image);
			jQuery('.loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: suteUrl+'/wp-content/plugins/Notification Hungerpass/ajax/merageNotification.php', 
				success: function(data) 
				{
					jQuery("#NotificationTitle").val("");
					jQuery("#NotificationEmail").val("");
					jQuery('.loader').hide();
					jQuery('#myModal').modal('hide');
					jQuery("section-table-details").empty().append(data);
					addPushNotifications();
				}
			});
		}
		
	});
});


function addPushNotifications() {
	var suteUrl = jQuery(".siteUrl").val();
	jQuery.ajax({
		type: "POST",
		url: suteUrl+'/wp-content/plugins/Notification Hungerpass/ajax/pushLiveContent.php', 
		data:{format:'raw'},
		success:function(resp){
			alert("Notification send successfully.");
		}
	});
}

/* function deleteNotification(event) {
	
	var result = jQuery(event).attr("id").split('myDelete_');
	var finalVar = result[1];
	var suteUrl = jQuery(".siteUrl").val();
	jQuery.ajax({
		type: "POST",
		url: suteUrl+'/wp-content/plugins/Notification Hungerpass/ajax/deleteNotification.php', 
		data:{finalVar:finalVar,format:'raw'},
		success:function(resp){
			jQuery(event).parent().parent().remove();
		}
	});
} */

jQuery(function ($) {
	jQuery('#addcity').validate({

		rules: {
			promo_code: {
				required: true
			},
			discount_price: {
				required: true
			},
			start_date: {
				required: true
			},
			end_date: {
				required: true
			}
		},
		submitHandler: function (form) {
			var suteUrl = jQuery(".suteUrl").val();
			jQuery.ajax({
				type: "POST",
				data: jQuery(form).serialize(),
				url: suteUrl + '/wp-content/plugins/olu app/ajax/promocodeSave.php/?lang=en',
				success: function (data) {
					toastr.success("Hecho exitosamente");
					setTimeout(function () { location.reload(); }, 3000);
				}
			});
		}

	});
});


function accpetMyUpdate(updateID) {
	var suteUrl = jQuery(".suteUrl").val();
	jQuery.ajax({
		type: "POST",
		data: { updateID: updateID, format: 'raw' },
		url: suteUrl + '/wp-content/plugins/olu app/ajax/acceptUpdate.php/?lang=en',
		success: function (data) {
			toastr.success("Hecho exitosamente");
			setTimeout(function () { location.reload(); }, 3000);
		}
	});
}
function approveMyTrainer(userID) {
	var suteUrl = jQuery(".suteUrl").val();
	jQuery.ajax({
		type: "POST",
		data: { format: 'raw' },
		url: suteUrl + '/api/trainerApprove/?userID=' + userID,
		success: function (data) {
			toastr.success("Hecho exitosamente");
			setTimeout(function () { location.reload(); }, 3000);
		}
	});
}
function rejectUpdate(updateID) {
	var suteUrl = jQuery(".suteUrl").val();
	jQuery.ajax({
		type: "POST",
		data: { updateID: updateID, format: 'raw' },
		url: suteUrl + '/wp-content/plugins/olu app/ajax/rejectUpdate.php/?lang=en',
		success: function (data) {
			toastr.success("Hecho exitosamente");
			setTimeout(function () { location.reload(); }, 3000);
		}
	});
}