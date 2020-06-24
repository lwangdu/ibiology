jQuery(document).ready(function( $ ) {
    $('.expanded-talks').DataTable({
        "columnDefs": [
        {
            "targets": [ 5 ],
            "visible": false,
            "searchable": true
        },
            {
                "targets": 'nosort',
                "orderable": false
        }],
        "pageLength": 1000
    });
} );