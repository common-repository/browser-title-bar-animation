(function($){

	var animation_interval;

	var tbas_front = {
		
		animation_show		: 'user-switch',
		animation_type		: 'typing',
		animation_speed		: 300,
		original_title		: null,
		animation_title		: null,
		countdown_duration	: 30,
		countdown_title		: '{{countdown}}',
		countdown_timer		: 30,

		count: 0,

		init : function() {
			
			
			this.original_title = document.title;
			this.animation_title = tbas_options.animation_title !== '' ? tbas_options.animation_title : document.title;
			
			this.animation_show = tbas_options.animation_show;
			this.animation_type = tbas_options.animation_type;
			this.animation_speed = parseInt( tbas_options.animation_speed );
			
			this.countdown_duration = parseInt( tbas_options.countdown_duration ) * 60;
			this.countdown_title = tbas_options.countdown_title;
			this.countdown_timer = this.countdown_duration;
			
			if ( 'blinking' === this.animation_type ) {
				
				if ( this.original_title === this.animation_title ) {
					this.animation_title = '***** - *****';
				}
			}

			console.log( this.animation_type );
			console.log( this.original_title );
			console.log( this.animation_title );
			console.log( this.animation_show );
			if ( 'always' === this.animation_show ) {
				tbas_front.start_animate();
			}else {
				this.bind_animate_events();
			}
		},

		bind_animate_events : function() {
			
			$(window).blur(function() {
				tbas_front.start_animate();
			});
			
			$(window).focus(function() {
                tbas_front.stop_animate();
            });

		},

		start_animate : function() {
			
			switch (tbas_front.animation_type) {
				case 'typing':
                    tbas_front.typing( 'yes' );
					break;
				case 'scrolling':
                    tbas_front.scrolling( 'yes' );
                    break;
				case 'blinking':
                    tbas_front.blinking( 'yes' );
					break;
				case 'countdown':
					tbas_front.countdown( 'yes' );
					break;
				default :
					tbas_front.typing( 'yes' );
					break;
            }
		},

		stop_animate : function() {
			switch (tbas_front.animation_type) {
				case 'typing':
                    tbas_front.typing( 'no' );
					break;
				case 'scrolling':
					tbas_front.scrolling( 'no' );
					break;
                case 'blinking':
                    tbas_front.blinking( 'no' );
					break;
				case 'countdown':
					tbas_front.countdown( 'no' );
					break;
				default :
					tbas_front.typing( 'no' );
					break;
            }
		},

		blinking : function( start ) {

			if ( 'yes' === start ) {
				
				animation_interval = window.setInterval(function() {
					document.title = tbas_front.original_title == document.title ? tbas_front.animation_title : tbas_front.original_title;
				}, tbas_front.animation_speed );
				
			} else {

				document.title = tbas_front.original_title; 
				window.clearInterval( animation_interval );
			}
		},

		typing: function( start ) {
			
			
			if ( 'yes' === start ) {
				
				animation_interval = window.setInterval(function() {
					
					document.title = tbas_front.animation_title.substring( 0, ( tbas_front.count + 1 ) );
					
					if ( tbas_front.count === tbas_front.animation_title.length) {
						tbas_front.count = 0;
					} else {
						tbas_front.count++;
					}

				}, tbas_front.animation_speed );
				
			} else {

				document.title = tbas_front.original_title; 
				window.clearInterval( animation_interval );
			}
			
		},
		
		scrolling: function( start ) {
			
			if ( 'yes' === start ) {
				
				animation_interval = window.setInterval(function() {
					
					document.title = tbas_front.animation_title.substring(
							tbas_front.count,
							tbas_front.animation_title.length
						) + " --- " +
						tbas_front.animation_title.substring(
							0,
							tbas_front.count
						);

					tbas_front.count++;
					
					if ( tbas_front.count > tbas_front.animation_title.length ) {
						tbas_front.count = 0;
					}
				}, tbas_front.animation_speed );
				
			} else {

				document.title = tbas_front.original_title; 
				window.clearInterval( animation_interval );
			}
			
		},
		countdown: function( start ) {
			
			if ( 'yes' === start ) {
				
				animation_interval = window.setInterval(function () {
					minutes = parseInt(tbas_front.countdown_timer / 60, 10)
					seconds = parseInt(tbas_front.countdown_timer % 60, 10);
			
					minutes = minutes < 10 ? "0" + minutes : minutes;
					seconds = seconds < 10 ? "0" + seconds : seconds;
			
					document.title = tbas_front.countdown_title.replace( '{{countdown}}', minutes + ":" + seconds );
			
					if (--tbas_front.countdown_timer < 0) {
						tbas_front.countdown_timer = tbas_front.countdown_duration;
					}
				}, 1000);
			} else {

				document.title = tbas_front.original_title; 
				window.clearInterval( animation_interval );
			}
        },
	};

	$(document).ready(function($) {
		
		var enable_on_focus = tbas_options.enable_on_focus;
		
		if ( 'yes' === enable_on_focus ) {
			return;
		}

		tbas_front.init();
	
	});
})(jQuery);