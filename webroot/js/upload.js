define(['jquery', 'basepath'], function ($, basepath) {
    return function (id, path) {
        $.ajax({
            type: "POST",
            url: basepath + "gtw_services/services/upload/",
            dataType: 'json',
            data: {
                id: $('#service-id').val(),
                file_id: id,
            },
            success: function (response, status) {
                
                if (response.success) {
                    loadServiceFiles(getFilesUrl);
                } else {
                    loadServiceFiles(getFilesUrl);
                }
            },
            error: function (response, status) {
                console.log(response);
                console.log(status);
                $('#contact-alert').html(
                    '<div class="alert alert-dismissable alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<strong>Error:</strong> unable to Upload file' +
                    '</div>'
                );
            }
        });
    };

});
