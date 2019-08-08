<?php

namespace liod\helpers;

class cmb2{
	
	static function get_all_events_as_option(){
		
		$get_post_args = array(
			'post_type' => 'event',
		);

		$options = array();
		foreach ( get_posts( $get_post_args ) as $post ) {
			$title = get_the_title( $post->ID );
			$options[$post->ID] = $title;

		}

		return $options;
	
	}
	
	static function get_video_types(){
		
		$options = array(
				'premium' 	=> __( 'Premium', 'cmb2' ),
				'free'   	=> __( 'Free', 'cmb2' )
			);
			
		return $options;
		
	}
	
	
	static function get_all_od_products(){
		
		$args = array(
			'type' => array('event')
		);

		foreach ( wc_get_products( $args ) as $product ) {
			$title = $product->get_title();
			$options[$product->get_id()] = $title;

		}

		return $options;		

		
	}
	
}

?>