jQuery(document).ready( function($) {


	/*
	// Settings taxonomy and terms
	settings_taxonomy_ajax_update = function() {
		post_type = $('#ftp-post-type').val();

		$('#ftp-post-taxonomy').parent().html('<p id="ftp-post-taxonomy">Loading taxonomies ...</p>');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ftp_get_object_taxonomies',
				post_type: post_type,
				source: 'settings'
			},
			complete: function( jqXHR, status ) {
				data = jqXHR.responseText;
				// Update the element with the data recieved from server
				$('#ftp-post-taxonomy').parent().html( data );
				// RE-ATTACH HANDLERS
				$('select#ftp-post-taxonomy').on( 'change', settings_tax_terms_ajax_update );
				// Update the terms
				settings_tax_terms_ajax_update();
			},
			dataType: 'json'
		});
	};
	$('select#ftp-post-type').ready( settings_taxonomy_ajax_update );
	$('select#ftp-post-type').on( 'change', settings_taxonomy_ajax_update );


	settings_tax_terms_ajax_update = function() {
		taxonomy = $('#ftp-post-taxonomy').val();
		$('#ftp-post-terms').parent().html('<p id="ftp-post-terms">Loading taxonomy terms ...</p>');
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ftp_get_taxonomy_terms',
				taxonomy: taxonomy,
				source: 'settings'
			},
			complete: function( jqXHR, status ) {
				data = jqXHR.responseText;
				// Update the element with the data recieved from server
				$('#ftp-post-terms').parent().html( data );
				$('#ftp-post-terms').prop('disabled', false);
			},
			dataType: 'json'
		});
	};
	$('select#ftp-post-taxonomy').on( 'change', settings_tax_terms_ajax_update );




	// Meta fields taxonomy and terms
	metabox_taxonomy_ajax_update = function() {
		post_type = $('select#wprss_ftp_post_type').val();
		$('#wprss_ftp_post_taxonomy').parent().html('<p id="wprss_ftp_post_taxonomy">Loading taxonomies ...</p>');
		$('#wprss_ftp_post_terms').prop( 'disabled', true );
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ftp_get_object_taxonomies',
				post_type: post_type,
				source: 'meta',
				post_id: $('#wprss-ftp-post-id').attr('data-post-id'),
			},
			complete: function( jqXHR, status ) {
				data = jqXHR.responseText;
				// Update the element with the data recieved from server
				$('#wprss_ftp_post_taxonomy').parent().html( data );
				// RE-ATTACHED THE HANDLERS
				$('select#wprss_ftp_post_taxonomy').on( 'change', metabox_terms_ajax_update );
				// Update the terms
				metabox_terms_ajax_update();
			},
			dataType: 'json'
		});
	};
	$('select#wprss_ftp_post_type').ready( metabox_taxonomy_ajax_update );
	$('select#wprss_ftp_post_type').on( 'change', metabox_taxonomy_ajax_update );
	*/


	metabox_terms_ajax_update = function() {
		tax = ( $('#wprss_ftp_post_taxonomy').is('select') )? $('#wprss_ftp_post_taxonomy').val() : '';
		$('#wprss_ftp_post_terms').parent().html('<p id="wprss_ftp_post_terms">' + wprss_ftp_admin_scripts.loading_taxonomies + '</p>');
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'ftp_get_taxonomy_terms',
				taxonomy: tax,
				post_id: $('#wprss-ftp-post-id').attr('data-post-id'),
				source: 'meta'
			},
			complete: function( jqXHR, status ) {
				data = jqXHR.responseText;
				// Update the element with the data recieved from server
				$('#wprss_ftp_post_terms').parent().html( data );
			},
			dataType: 'json'
		});
	};
	$('select#wprss_ftp_post_terms').on( 'change', metabox_terms_ajax_update );


	var post_type_label = $('td label[for="wprss_ftp_post_type"] span');
	var post_type_dropdown = $('#wprss_ftp_post_type');
	var original_post_type_label = post_type_label.text();

	var checkIfFeedItemPostType = function(){
		if ( post_type_dropdown.val() === 'wprss_feed_item' ) {
			post_type_label.html(wprss_ftp_admin_scripts.feed_post_type_warning);
		} else {
			post_type_label.text( original_post_type_label );
		}
	};
	
	post_type_dropdown.on( 'change', checkIfFeedItemPostType );
	checkIfFeedItemPostType();
	
});

(function($) {

	// Meta Word Trimming
	$(window).load( function() {
		// Word limit enabled dropdown field
		var word_limit_enabled = $( '#wprss_ftp_word_limit_enabled' );
		// The <tr> rows
		var rows = $('#wprss-ftp-word-trimming-metabox .wprss-form-table tbody tr');

		// Returns the enabled field selected value
		var get_word_limit_enabled_option = function() {
			return word_limit_enabled.find('option:selected').val();
		};
		// Returns true|false if word limit is enabled or not
		var is_word_limit_enabled = function() {
			return get_word_limit_enabled_option() == 'true';
		};
		// Hides the second and third rows if the word limit is not enabled
		var hide_other_rows = function() {
			rows.not(':first-child').toggle( is_word_limit_enabled() );
		};

		// When the word limit enabled field changes value, update the rows
		word_limit_enabled.on('change', hide_other_rows);
		// Also run for first time on page load
		hide_other_rows();
	});

})(jQuery)
