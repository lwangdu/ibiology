jQuery(document).ready(function($) {
    $('.directory-table').DataTable({
    	"dom": '<"toolbar">frtip',
    	"paging":   false,
    	 "order": [[ 0, 'asc' ]]
    });
     $("div.toolbar").html('<b><a href="http://phonebook.lbl.gov">Visit the LBL Phonebook</a></b>');
} );