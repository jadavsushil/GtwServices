define(function (require) {
    // Dependencies
    var $ = require('jquery');
    var jqueryvalidate = require('jqueryvalidate');

    $(document).ready(function () {
        $('#ServiceAddEditForm').validate({
            errorClass: "text-danger",
            rules: {
                "data[Service][title]": {
                    required: true
                }
            },
            messages: {
                "data[Service][title]": {
                    required: "Please enter Title"
                }
            }
        });
        loadServiceFiles(getFilesUrl);
    });
});
function loadServiceFiles(path) {
    jQuery.ajax({
        'url': path,
        type: 'POST',
        'success': function (response) {
            jQuery("#uploadedServiceFiles").html(response);
        }
    });
}
