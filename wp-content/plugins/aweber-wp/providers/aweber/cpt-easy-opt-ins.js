jQuery( document ).ready( function( $ ) {

	var $authorization_code = $( '[name="fca_eoi[aweber_authorization_code]"]' );
	var $post_id = $( '#post_ID' );
	var $lists = $( '[name="fca_eoi[aweber_list_id]"]' );
	var $lists_wrapper = $( '#aweber_list_id_wrapper' );
	var $tags_wrapper = $( '#aweber_tags_wrapper' );

	aweber_toggle_fields();

	fca_eoi_provider_status_setup( 'aweber', $authorization_code );

	$authorization_code.bind( 'input', function() {
		if ( ! fca_eoi_provider_is_value_changed( $( this ) ) ) {
			return;
		}

		fca_eoi_provider_status_set( 'aweber', fca_eoi_provider_status_codes.loading );

		var data = {
			'action': 'fca_eoi_aweber_get_lists', /* API action name, do not change */
			'aweber_authorization_code' : $authorization_code.val().trim(),
			'post_id' : $post_id.val()
		};

		$.post( ajaxurl, data, function( response ) {

			var lists = JSON.parse( response );

			fca_eoi_provider_status_set( 'aweber', Object.keys(lists).length > 1
				? fca_eoi_provider_status_codes.ok
				: fca_eoi_provider_status_codes.error );

			var $lists = $( '<select class="select2" style="width: 27em;" name="fca_eoi[aweber_list_id]" >' );

			for ( list_id in lists ) {
				$lists.append( '<option value="' + list_id + '">' + lists[ list_id ] + '</option>' );
			}

			// Replace dropdown with new list of lists, apply Select2 then show
			$( '[name="fca_eoi[aweber_list_id]"]' ).select2( 'destroy' );
			$( '[name="fca_eoi[aweber_list_id]"]' ).replaceWith( $lists );
			$( '[name="fca_eoi[aweber_list_id]"]' ).select2();
			aweber_toggle_fields();
		} );
	})

	// Confirm before unlocking the authorization_code field
	$authorization_code.filter( '[readonly=readonly]' ).click( function( e ) {
		var confirm = window.confirm( 'Due to limitations in the AWeber API, The plugin accepts only one AWeber app, thus, changing the authorization code in this form will change it in all other forms.\n\nDo you really want to change the authorization code?' );
		if ( confirm ) {
			$authorization_code.removeAttr( 'readonly' ).val( '' );
		}
	} );

	/**
	 * Show/hide some fields if there are/aren't list options
	 *
	 * Don't forget that there is always the option "Not Set", 
	 * so take it into consideration when cheking the number of options
	 */
	function aweber_toggle_fields() {

		var options = $( 'option', '[name="fca_eoi[aweber_list_id]"]' );

		if ( options.length > 1 ) {
			$lists_wrapper.show( 'fast' );
			$tags_wrapper.show( 'fast' );
		} else {
			$lists_wrapper.hide();
			$tags_wrapper.hide();
		}
	}
	
	function aweber_create_tag( name ) {
		if ( typeof (name) === 'string' && name ) {
			var html = '<span class="aweber-tag-wrapper"><span class="dashicons dashicons-dismiss aweber-tag-delete"></span><span class="aweber-tag">' + name.trim() + '</span></span>'
			$('#aweber-tag-div').append( html )
			update_tags_hidden_input()
			add_delete_tag_handlers()
		}
	}
	
	function update_tags_hidden_input() {
		var tagList = ''
		$('.aweber-tag').each(function(){
			tagList += $(this).html() + ', '
		})

		$('#aweber_tag_hidden_input').val(tagList.slice(0, tagList.length - 2))
	}
	
	$('#aweber_add_tag').click(function(){
		var input = $('#aweber_tag_text_input').val()

		var tags = input.split(',')
		for (var i = 0, len = tags.length; i < len; i++) {
			aweber_create_tag( tags[i] )
		}

		$('#aweber_tag_text_input').val('')
	})
	
	$('#aweber_tag_text_input').keyup(function(e){
		if(e.keyCode == 13)	{
			$('#aweber_add_tag').click()
		}
	})
	
	function add_delete_tag_handlers() {
		$('.aweber-tag-delete').unbind('click')
		$('.aweber-tag-delete').click(function(){
			$(this.parentNode).remove()
			update_tags_hidden_input()
		})
	}
	
	
	
	function load_tags() {
		if ( fcaEoiAweberSettings.hasOwnProperty( 'tags' ) && fcaEoiAweberSettings.tags !== '' ) {
			var tags = fcaEoiAweberSettings.tags.split(', ')
			for (var i = 0, len = tags.length; i < len; i++) {
			  aweber_create_tag(tags[i])
			}			
		}
	}
	load_tags()

});
