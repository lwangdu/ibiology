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
        "pageLength": 300,
        "paging":   false,
        "language": {
            "search": "Search videos with educator resources:"
        }
    });
} );