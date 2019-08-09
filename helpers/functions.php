<?php

/**
 * LIOD Functions
 *
 * Functions, Class Independent for use again the current wp_query
 *
 * @package LIOD
 * @subpackage Functions
 */
/**

/**
 * is WP QUERY SET?
 * 
 * @return BOOL
 */	
function wp_query_checker(){
	
	global $wp_query;
	
	if ( ! isset( $wp_query ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'Conditional query tags do not work before the query is run. Before then, they always return false.' ), '3.1.0' );
			return false;
	}
	
	return true;
	
}

/**
 * Is this POST an EVENT Type?
 * 
 * @return BOOL
 */	
if(!function_exists('liod_is_event')){
	function liod_is_event(){
		
		return get_post_type() == liod()->custom_post_types['event']->post_type;
		
	}
}


/**
 * Is this POST an VIDEO Type?
 * 
 * @return BOOL
 */	
if(!function_exists('liod_is_video')){
	function liod_is_video(){
			
		return get_post_type() == liod()->custom_post_types['video']->post_type;
		
	}
}

/**
 * Has User purchased the given video or event?
 * 
 * @return BOOL
 */	
if(!function_exists('liod_event_is_purchased')){
	function liod_event_is_purchased(){
		
		if(liod_is_event()){
			return liod()->has_purchased_event( get_the_ID() );
		}
		
		if(liod_is_video()){
			$event_id = liod()->get_event_from_video( get_the_ID() );
			return liod()->has_purchased_event( $event_id );
		}
				
	}
}

/**
 * Current Event
 * 
 * @return String
 */	
if(!function_exists('liod_get_the_event')){
	function liod_get_the_event(){
		
		if(liod_is_event()){
			return liod()->get_event( get_the_ID() );
		}
						
	}
}

/**
 * GET Current Event Category
 * 
 * @return String
 */	
if(!function_exists('liod_get_the_event_category')){
	function liod_get_the_event_category(){
		
		if(liod_is_event()){
			foreach(get_the_terms(liod_get_the_event()->ID,'event_category') as $term){
				return ($term->name);
			}
		}
						
	}
}

/**
 * ECHO Current Event Category
 * 
 * @return null
 */	
if(!function_exists('liod_the_event_category')){
	function liod_the_event_category(){
		echo liod_get_the_event_category();				
	}
}

/**
 * GET Current Event Topics
 * 
 * @return ARRAY STRING
 */	
if(!function_exists('liod_get_the_event_topics')){
	function liod_get_the_event_topics(){
		
		if(liod_is_event()){
			$terms = array();

			foreach(get_the_terms(liod_get_the_event()->ID,'event_topic') as $term){
				$terms[] = ($term->name);
			}
			return $terms;
		}
						
	}
}


/**
 * Echo Current Event Topics as List
 * 
 * @return null
 */	
if(!function_exists('liod_the_event_topics')){
	function liod_the_event_topics(){
		
		if(liod_is_event()){
			$terms = liod_get_the_event_topics();
			
			if(empty($terms))return;

			foreach($terms as $term){
				echo '
				<li>
					<a href="">' . $term . '</a>
				</li>';
			}
		}				
	}
}



/**
 * GET Current Event Payment Model
 * 
 * @return STRING
 */	
if(!function_exists('liod_get_the_event_payment_model')){
	function liod_get_the_event_payment_model(){
		
		global $wp_query;	
		
		if(liod_is_event()){
			return isset(liod_get_the_event()->postmeta->liod_event_payment_model) ? liod_get_the_event()->postmeta->liod_event_payment_model->meta_value : null;
		}
						
	}
}


/**
 * ECHO Current Event Payment Model
 * 
 * @return null
 */	
if(!function_exists('liod_the_event_payment_model')){
	function liod_the_event_payment_model(){
		echo liod_get_the_event_payment_model();				
	}
}


/**
 * GET Current Event Type
 * 
 * @return STRING
 */	
if(!function_exists('liod_get_the_event_type')){
	function liod_get_the_event_type(){
		
		global $wp_query;	
		
		if(liod_is_event()){
			return isset(liod_get_the_event()->postmeta->liod_event_type) ? liod_get_the_event()->postmeta->liod_event_type->meta_value : null;
		}
						
	}
}

/**
 * ECHO Current Event Type
 * 
 * @return null
 */	
if(!function_exists('liod_the_event_type')){
	function liod_the_event_type(){
		echo liod_get_the_event_type();				
	}
}


/**
 * GET Current Event Date
 * 
 * @return STRING
 */	
if(!function_exists('liod_get_the_event_date')){
	function liod_get_the_event_date(){
		
		global $wp_query;	
		
		if(liod_is_event()){
			if(isset(liod_get_the_event()->postmeta->liod_event_date)){
				return date("d-m-Y", strtotime(liod_get_the_event()->postmeta->liod_event_date->meta_value));
			}
			
			return null;
		}
						
	}
}

/**
 * ECHO Current Event Date
 * 
 * @return null
 */	
if(!function_exists('liod_the_event_date')){
	function liod_the_event_date(){
		echo liod_get_the_event_date();				
	}
}


/**
 * GET Current Event Location
 * 
 * @return STRING
 */	
if(!function_exists('liod_get_the_event_location')){
	function liod_get_the_event_location(){
		
		global $wp_query;	
		
		if(liod_is_event()){
			return isset(liod_get_the_event()->postmeta->liod_event_location) ? liod_get_the_event()->postmeta->liod_event_location->meta_value : null;
		}
						
	}
}

/**
 * ECHO Current Event Location
 * 
 * @return null
 */	
if(!function_exists('liod_the_event_location')){
	function liod_the_event_location(){
		echo liod_get_the_event_location();				
	}
}

/**
 * Is Event FREE
 * 
 * @return BOOL
 */	
if(!function_exists('liod_event_is_free')){
	function liod_event_is_free(){
		if(liod_get_the_event_payment_model() == 'free') return true;
		return false;			
	}
}

/**
 * Does event have any videos
 * 
 * @return BOOL
 */	
if(!function_exists('liod_event_has_videos')){
	function liod_event_has_videos(){
		if(liod_is_event()){
			return !empty(liod()->get_all_videos( get_the_ID() )) ? true : false;		
		}		
	}
}

/**
 * GET event videos
 * 
 * @return ARRAY VIDEO
 */	
if(!function_exists('liod_get_event_videos')){
	function liod_get_event_videos( $event_id = null ){
		if(liod_is_event()){
			return liod()->get_all_videos( get_the_ID() );		
		}		
	}
}


/**
 * GET all products that could be purchased to give access to event
 * 
 * @return ARRAY of WC PRODUCTS
 */	
if(!function_exists('liod_get_the_event_products')){
	function liod_get_the_event_products(){
		if(liod_is_event()){
			return (liod()->get_products_from_event( get_the_ID() ));
		}
	}
}

/**
 * Does this event have multiple product options
 * 
 * @return BOOL
 */	

if(!function_exists('liod_event_has_multiple_products')){
	
	function liod_event_has_multiple_products(){
		
		$products = liod()->get_products_from_event( get_the_ID() );
		
		if(count($products) > 1){
			return true;
		}else{
			return false;
		}
		
		
	}
		
}

/**
 * Generate buy buttons for every product available for event
 * 
 * @param arr     $arr Options [before, before_title, title, after_title, after, price]
 *
 * @return NULL
 */

if(!function_exists('liod_buy_buttons')){
	
	function liod_buy_buttons($arr = null){
		
		$products = liod()->get_products_from_event( get_the_ID() );

		foreach($products as $product){
			echo (isset($arr['before'])) ? $arr['before'] : '';	
			echo '<a href="' . $product->get_permalink() . '">';
			echo (isset($arr['before_title'])) ? $arr['before_title'] : '';			
			echo (isset($arr['title'])) ? $arr['title'] : $product->get_name();	
			echo (isset($arr['price'])) ? ' ' . get_woocommerce_currency_symbol() . $product->get_price() : '';				
			echo (isset($arr['after_title'])) ? $arr['after_title'] : '';				
			echo '</a>';			
			echo (isset($arr['after'])) ? $arr['after'] : '';	
		}
		
	}
		
}





