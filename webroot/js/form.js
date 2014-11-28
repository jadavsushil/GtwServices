define(function(require){
    // Dependencies
     var $            = require('jquery');
     var jqueryvalidate = require('jqueryvalidate');
	 
	$(document).ready(function() {
		$('#ServiceAddEditForm').validate({
			errorClass: "text-danger",
			rules:{
					"data[Service][title]":{
                        required: true
					}
			},
			messages:{
					"data[Service][title]":{
							required: "Please enter Title"
					}
			}
		});
		loadServiceFiles(uploadifySetting.getFilesUrl);
		$.ajax({
			url: require.toUrl("./jquery.uploadify.v2.1.4.min.js"),
			dataType: 'script',
			success: function(){			
				$('#serviceFiles').uploadify({
					'uploader'  : require.toUrl("./uploadify.swf"),
					'script'    : uploadifySetting.script,
					//'cancelImg' : '<i class="icon-remove-sign"></i>',
					'buttonText' : uploadifySetting.buttonText,
					'sizeLimit' : 1024*1024*10,
					'auto'      : true,
					'multi'       : true,
					'onAllComplete': function() {
						loadServiceFiles(uploadifySetting.getFilesUrl);
					}
				});
			},
			async: true
		});

		
	});
});
function loadServiceFiles(path){
	jQuery.ajax({
		'url': path,
		type: 'POST',
		'success': function (response) {
			jQuery("#uploadedServiceFiles").html(response);
		}
	});	
}