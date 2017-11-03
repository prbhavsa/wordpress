/* jshint asi: true */
jQuery( document ).ready(function($) {
	(function prepare_events() {
		for( var i = 0; i < fcaPcEvents.length; i++ ) {
			
			var eventName = fcaPcEvents[i].event
			var parameters = fcaPcEvents[i].parameters
			var triggerType = fcaPcEvents[i].triggerType
			var trigger = fcaPcEvents[i].trigger
			var apiAction = fcaPcEvents[i].apiAction
			
			switch ( triggerType ) {
				case 'css':
					$( trigger ).bind('click', { name: eventName, params: parameters, apiAction: apiAction }, function( e ){
						fb_api_call( e.data.apiAction, e.data.name, e.data.params )
					})
					break
				
				case 'hover':
					$( trigger ).bind('hover', { name: eventName, params: parameters, apiAction: apiAction, trigger: trigger }, function( e ){
						fb_api_call( e.data.apiAction, e.data.name, e.data.params )
						$( e.data.trigger ).unbind( 'hover' )
					})
					break
					
				case 'post':
					if ( fcaPcEvents[i].hasOwnProperty('delay') && fcaPcEvents[i].hasOwnProperty('scroll') ) {
						setTimeout( function( scrollTarget, apiAction, eventName, parameters ){
							$( window ).scroll( { 
								'scrollTarget': scrollTarget,
								'apiAction': apiAction,
								'eventName': eventName,
								'parameters': parameters
								}, function( e ) {
									if ( e.data.scrollTarget <= scrolled_percent() ) {
										$( window ).unbind( e )
										fb_api_call( apiAction, eventName, parameters )
									}
							}).scroll()
						}, fcaPcEvents[i].delay * 1000, fcaPcEvents[i].scroll, apiAction, eventName, parameters  )
						
						
					} else if ( fcaPcEvents[i].hasOwnProperty('delay') ) {
						setTimeout( fb_api_call, fcaPcEvents[i].delay * 1000, apiAction, eventName, parameters  )
					} else {
						fb_api_call( apiAction, eventName, parameters )
					}
					break
					
				case 'url':
					$('a').each(function(){
						if ( $(this).attr('href') === trigger ) {
							$(this).bind('click', { name: eventName, params: parameters, apiAction: apiAction }, function( e ){
								fb_api_call( e.data.apiAction, e.data.name, e.data.params )
							})
						}
					})
												
					break
			}

		}
	})()
	
	//SEARCH INTEGRATION
	if ( typeof fcaPcSearchQuery !== 'undefined' ) {
		fb_api_call('track', 'Search', fcaPcSearchQuery )
	}
	
	//QUIZ CAT INTEGRATION
	if ( typeof fcaPcQuizCatEnabled !== 'undefined' ) {

		$( '.fca_qc_start_button' ).click( function( e ){
			var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
			var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
			fb_api_call('trackCustom', 'QuizStart', { 'quiz_id': id, 'quiz_name': name } )
			return true
		})
		
		$( '.fca_qc_share_link' ).click( function( e ){
			var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
			var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
			fb_api_call('trackCustom', 'QuizShare', { 'quiz_id': id, 'quiz_name': name } )
			return true
		})
		
		$( '.fca_qc_submit_email_button' ).click( function( e ){
			if ( $(this).siblings('#fca_qc_email_input').val() ) {
				var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
				var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
				fb_api_call('track', 'Lead', { 'quiz_id': id, 'quiz_name': name } )
				return true
			}
		})
		
		$( '.fca_qc_score_title' ).on('DOMSubtreeModified', function( e ){
			if( !$(this).data('pixelcat') ) {
				$(this).data('pixelcat', true)
				var id = parseInt ( $(this).closest('.fca_qc_quiz').prop('id').replace('fca_qc_quiz_', '') )
				var name = $(this).closest('.fca_qc_quiz').find('.fca_qc_quiz_title').text()
				fb_api_call('trackCustom', 'QuizCompletion', { 'quiz_id': id, 'quiz_name': name, 'quiz_result': $(this).text() } )
			}
			return true
		})
	}
	
	//EDD INTEGRATION
	if ( typeof fcaPcEddCheckoutCart !== 'undefined' ) {
		fb_api_call( 'track', 'InitiateCheckout', fcaPcEddCheckoutCart)
		
		//ADDPAYMENTINFO
		$( '#edd_purchase_form' ).on( 'submit', function( e ){
			fb_api_call('track', 'AddPaymentInfo', fcaPcEddCheckoutCart )
			return true
		})
	}
	
	if ( typeof fcaPcEddProduct !== 'undefined' ) {
		//VIEWCONTENT
		if( fcaPcPost.edd_delay ) {
			setTimeout( fb_api_call, fcaPcPost.edd_delay * 1000, 'track', 'ViewContent', fcaPcEddProduct  )
		} else {
			fb_api_call( 'track', 'ViewContent', fcaPcEddProduct )
		}
		
		//ADD TO CART
		$( '.edd-add-to-cart' ).click( function( e ){
			fb_api_call( 'track', 'AddToCart', fcaPcEddProduct )
		})		
		//WISHLIST ( TODO )
		$( '.wl-add-to, .add_to_wishlist' ).click( function( e ){
			fb_api_call( 'track', 'AddToWishlist', fcaPcEddProduct )
		})
	}
	
	//PURCHASE
	if ( get_cookie( 'fca_pc_edd_purchase' ) ) {
		fb_api_call( 'track', 'Purchase', JSON.parse( decodeURIComponent ( get_cookie( 'fca_pc_edd_purchase' ).replace(/\+/g, '%20') ) ) )
		set_cookie( 'fca_pc_edd_purchase', '' )
	}
	
	//REMOVE ADVANCED MATCHING COOKIE IF APPLICABLE
	if ( get_cookie( 'fca_pc_advanced_matching' ) ) {
		set_cookie( 'fca_pc_advanced_matching', '' )
	}
	
	//WOO INTEGRATION
	if ( typeof fcaPcWooAddToCart !== 'undefined' ) {
		fb_api_call( 'track', 'AddToCart', fcaPcWooAddToCart )
	}
	
	if ( typeof fcaPcWooCheckoutCart !== 'undefined' ) {
		fb_api_call( 'track', 'InitiateCheckout', fcaPcWooCheckoutCart)
		
		$( 'form.checkout' ).on( 'checkout_place_order', function( e ){
			fb_api_call('track', 'AddPaymentInfo', fcaPcWooCheckoutCart )
			return true
		})
	}
	
	if ( typeof fcaPcWooPurchase !== 'undefined' ) {
		fb_api_call( 'track', 'Purchase', fcaPcWooPurchase)
	}
	
	if ( typeof fcaPcWooProduct !== 'undefined' ) {
		if( fcaPcPost.woo_delay ) {
			setTimeout( fb_api_call, fcaPcPost.woo_delay * 1000, 'track', 'ViewContent', fcaPcWooProduct  )
		} else {
			fb_api_call( 'track', 'ViewContent', fcaPcWooProduct )
		}
		
		//WISHLIST
		$( '.wl-add-to, .add_to_wishlist' ).click( function( e ){
			fb_api_call( 'track', 'AddToWishlist', fcaPcWooProduct )
		})
	}
	
	if ( fcaPcDebug.debug ) {
		console.log ( 'pixel cat events:' )
		console.log ( fcaPcEvents )
		console.log ( 'pixel cat post:' )
		console.log ( fcaPcPost )
	}
	
	function fb_api_call( name, action, params ) {
		fbq( name, action, add_auto_event_params( params ) )
	}
	
	function set_cookie( name, value ) {
		document.cookie = name + "=" + value + ";path=/"
	}

	function get_cookie( name ) {
		var value = "; " + document.cookie
		var parts = value.split( "; " + name + "=" )
		
		if ( parts.length === 2 ) {
			return parts.pop().split(";").shift()
		} else {
			return false
		}
	}
	
	function get_url_param( parameterName ) {
		var	tmp = []
		var items = location.search.substr(1).split( '&' )
		
		for ( var k = 0; k<items.length; k++ ) {
			tmp = items[k].split( '=' )
			if ( tmp[0] === parameterName ){
				return decodeURIComponent( tmp[1] ).replace( /\+/g, ' ' )
			}
		}
		return null
	}
	
	function add_auto_event_params( parameters ) {

		for ( var prop in parameters ) {
			//IGNORE ARRAYS
			if ( typeof( parameters[prop] ) === 'string' ) {
				parameters[prop] = parameters[prop].replace( '{post_id}', fcaPcPost.id )
					 .replace( '{post_title}', fcaPcPost.title )
					 .replace( '{post_type}', fcaPcPost.type )
					 .replace( '{post_category}', fcaPcPost.categories.join(', ') )
			}
		}
		
		if ( fcaPcPost.utm_support ) {
			parameters = add_utm_params( parameters )
		}
		
		if ( fcaPcPost.user_parameters ) {
			parameters = add_user_params( parameters )
		}
		
		return parameters
		
	}
	
	function add_user_params( parameters ) {
		var user_params = [
			'referrer',
			'language',
			'logged_in',
			'post_tag',
			'post_category',
		]
		
		for ( var k = 0; k<user_params.length; k++ ) {
			if ( fcaPcUserParams[user_params[k]] ) {
				parameters[user_params[k]] = fcaPcUserParams[user_params[k]]
			}
		}
		
		return parameters
		
	}
	
	function scrolled_percent() {
		var top = $( window ).scrollTop()
		var height = $( document ).height() - $( window ).height()
		if ( height == 0 ) {
			return 100
		}
		return 100 * ( top / height )
	}
	
	function add_utm_params( parameters ) {
		var utm_params = [
			'utm_source',
			'utm_medium',
			'utm_campaign',
			'utm_term',
			'utm_content',
		]
		
		for ( var j = 0; j<utm_params.length; j++ ) {
			if ( get_url_param( utm_params[j] ) !== null ) {
				parameters[utm_params[j]] = get_url_param( utm_params[j] )
			}
		}

		return parameters
	}


})