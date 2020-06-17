jQuery(document).ready(function( $ ) {
    $('.expanded-talks').DataTable({
        "columnDefs": [
        {
            "targets": [ 5 ],
            "visible": false,
            "searchable": true
        }]
    });
} );