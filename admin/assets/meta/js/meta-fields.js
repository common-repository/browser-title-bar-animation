(function($){

	var hide_show_tooltip = function (){
		
		$('.tbas-field-heading-help').click(function(){
			var tip_wrap = $(this).closest('.tbas-field-row');
	        	closest_tooltip = tip_wrap.find('.tbas-tooltip-text');
	        	
	        	closest_tooltip.toggleClass('show-tooltip');
	    });

	}

	var hide_show_override_fields = function() {

		if ( $('input#tbas_override_global').is(":checked") ) {
			$('.tbas-override-meta-settings-fields').show();
		}else{
			$('.tbas-override-meta-settings-fields').hide();
		}
	};

	var hide_show_animation_type_fields = function() {

		var animation_type = $('.field-tbas_animation_type select').val();

		if ( 'countdown' === animation_type ) {
			$('.field-tbas_animation_speed').hide();
			$('.field-tbas_animation_title').hide();
			$('.field-tbas_countdown_duration').show();
			$('.field-tbas_countdown_title').show();
		}else{
			$('.field-tbas_animation_speed').show();
			$('.field-tbas_animation_title').show();
			$('.field-tbas_countdown_duration').hide();
			$('.field-tbas_countdown_title').hide();
		}
	};
		

	$(document).ready(function($) {
		
		hide_show_tooltip();

		hide_show_override_fields();

		hide_show_animation_type_fields();
		
		$('.field-tbas_animation_type select').on( 'change', function(e) {
			hide_show_animation_type_fields();
		});

		$('input#tbas_override_global').on( 'change', function(e) {
			hide_show_override_fields();
		});
	});

	
})(jQuery);