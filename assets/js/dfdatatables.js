jQuery(document).ready(function($) {
    $('#dfdatatable').on('dblclick', 'tr', function () {
        var url = $(this).data('action');
        if (url) {
            var target = $(this).data('target') || '_self';
            if (target === '_blank') {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
        }
    });
});
