( function( $ ) {

	var animation_type_dependency = function() {
		
		var animation_type = $('select#tbas_animation_type').val();
		
		hide_show_screenshots( animation_type );
		hide_show_fields( animation_type );
	};

    var hide_show_screenshots = function( animation_type ) {
        $('.tbas-screenshot').hide();
        $('.tbas-screenshot-' + animation_type ).show();
	};

	var hide_show_fields = function( animation_type ) {

		if ( 'countdown' === animation_type ) {
			$('#tbas_animation_speed').closest('tr').hide();
			$('#tbas_animation_title').closest('tr').hide();
			$('#tbas_countdown_duration').closest('tr').show();
			$('#tbas_countdown_title').closest('tr').show();
		}else{
			$('#tbas_animation_speed').closest('tr').show();
			$('#tbas_animation_title').closest('tr').show();
			$('#tbas_countdown_duration').closest('tr').hide();
			$('#tbas_countdown_title').closest('tr').hide();
		}
	};

	$(document).ready(function($) {
		
		animation_type_dependency();
		
		$('select#tbas_animation_type').on( 'change', function(e) {
			animation_type_dependency();
		});
	});
} )( jQuery ); 