<?php

namespace liod\custom_post_types;

class event extends \liod\custom_post_types\custom_post_type{
	
	public function setup(){

		$this->post_type 						= "event";
		$this->post_type_icon 					= 'dashicons-tickets-alt';
		$this->post_type_display_name 			= 'Event';
		$this->post_type_display_name_plural 	= "Events";
		
		$this->post_type_enable_tags 			= true;
		$this->post_type_enable_featured_image 	= false;	
		$this->post_type_enable_content 		= false;	
		$this->post_type_enable_title 			= false;		
		$this->post_type_enable_excerpt 		= false;
		$this->post_type_enable_author 			= false;

		
		/* Event Categories */
		$this->add_taxonomy('event_category', array(
			'taxonomy'						=>	'event_category',
			'taxonomy_display_name'			=> 	'Event Category',
			'taxonomy_display_name_plural' 	=> 	'Event Categories',
		));
		
		/* Event Topics */
		$this->add_taxonomy('event_topic', array(
			'taxonomy'						=>	'event_topic',
			'taxonomy_display_name'			=> 	'Event Topic',
			'taxonomy_display_name_plural' 	=> 	'Event Topics',
		));	
		
		/* Event Payment Models */
		$this->add_taxonomy('event_payment_model', array(
			'taxonomy'						=>	'event_payment_model',
			'taxonomy_display_name'			=> 	'Event Payment Model',
			'taxonomy_display_name_plural' 	=> 	'Event Payment Models',
			'taxonomy_allow_new'			=> 	false,
			'taxonomy_terms'				=> 	array(
													array('term' => 'free', 'display_name'=>'Free'),
													array('term' => 'premium', 'display_name' =>'Premium'
													)
												),
		));			

		/* Event Types */
		$this->add_taxonomy('event_type', array(
			'taxonomy'						=>	'event_type',
			'taxonomy_display_name'			=> 	'Event Type',
			'taxonomy_display_name_plural' 	=> 	'Event Types',
			'taxonomy_allow_new'			=> 	false,
			'taxonomy_terms'				=> 	array(
													array(
														'term' => 'livestream', 
														'display_name'=>'Livestream'),
													array(
														'term' => 'catchup', 
														'display_name' =>'Catch-Up'
													)
												),		
		));		
		
	}
	
	public function post_type_meta(){
		
		/* Handles Saving standard elements using CMB2 */
		add_filter( 'cmb2_override_excerpt_meta_value', function ( $data, $post_id ) {
			return get_post_field( 'post_excerpt', $post_id );
		}, 10, 2 );
		
		add_filter( 'cmb2_override_post_title_meta_value', function ( $data, $post_id ) {
			return get_post_field( 'post_title', $post_id );
		}, 10, 2 );	

		add_filter( 'cmb2_override_content_meta_value', function ( $data, $post_id ) {
			return get_post_field( 'post_content', $post_id );
		}, 10, 2 );				
		
		add_filter( 'cmb2_override_excerpt_meta_save', '__return_true' );
		add_filter( 'cmb2_override_post_title_meta_save', '__return_true' );		
		add_filter( 'cmb2_override_content_meta_save', '__return_true' );
		
		$cmb = \new_cmb2_box( array(
			'id'           		=> $this->prefix . 'details_metabox',
			'title'        		=> __( 'Details', 'cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );

				$cmb->add_field( array(
					'name'      		=> 'Title',
					'desc'      		=> 'The title of this video.',
					'id'        		=> 'post_title',					
					'default' 			=> '',					
					'type'      		=> 'text',
				) );
		
				$cmb->add_field( array(
					'name'      		=> 'Summary',
					'desc'      		=> 'A Summary about this event.',
					'id'        		=> 'excerpt',					
					'type'      		=> 'textarea',
					'escape_cb' 		=> false,
				) );	
				
				$cmb->add_field( array(
					'name'      		=> 'Description',
					'desc'      		=> 'Event Description',
					'id'        		=> 'content',					
					'type'      		=> 'wysiwyg',
					'escape_cb' 		=> false,
				) );
				
				$cmb->add_field( array(
					'name' 				=> __( 'Location', 'cmb2' ),
					'id' 				=> $this->prefix . 'location',
					'type' 				=> 'text',
				) );	

				$cmb->add_field( array(
					'name' 				=> __( 'Event Date', 'cmb2' ),
					'id' 				=> $this->prefix . 'date',
					'type' 				=> 'text_date',
				) );				

				$cmb->add_field( array(
					'desc'    => 'Upload/Select an image.',
					'name'    => 'Image',
					'id'      => '_thumbnail',					
					'type'    => 'file',
					'options' => array(
						'url' => false,
					),
					'text' => array(
						'add_upload_file_text' => 'Add Image'
					),
				) );					
				
				
				
		$cmb = \new_cmb2_box( array(
			'id'           		=> $this->prefix . 'categories_metabox',
			'title'        		=> __( 'Categories', 'cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );	
		
				$cmb->add_field( array(
					'name' 				=> __( 'Category', 'cmb2' ),
					'desc'           	=> 'What is the category for this event',
					'id'             	=> $this->prefix . 'category',
					'taxonomy'       	=> 'event_category', 
					'show_option_none' 	=> false,
					'type'           	=> 'taxonomy_select',
					'remove_default' 	=> 'true', 
					'query_args' => array(
					),
				) );
				
				$cmb->add_field( array(
					'name' 				=> __( 'Event Topics', 'cmb2' ),
					'desc'    			=> 'What topics were covered at this event?',
					'id' 				=> $this->prefix . 'topics',
					'type'    			=> 'taxonomy_multicheck',
					'remove_default' 	=> 'true', 					
					'taxonomy'       	=> 'event_topic', 
				) );	

				$cmb->add_field( array(
					'name' 				=> __( 'Event Type', 'cmb2' ),
					'desc'           	=> 'What type of event is this',
					'id'             	=> $this->prefix . 'type',
					'taxonomy'       	=> 'event_type', 
					'show_option_none' 	=> false,					
					'type'           	=> 'taxonomy_select',
					'remove_default' 	=> 'true', 
					'query_args' => array(
					),
				) );
								
		$cmb = \new_cmb2_box( array(
			'id'           		=> $this->prefix . 'payment_metabox',
			'title'        		=> __( 'Payment', 'cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );					
		
				$cmb->add_field( array(
					'name' 				=> __( 'Event Payment Model', 'cmb2' ),
					'desc'           	=> 'What is the payment model for this event',
					'id'           		=> $this->prefix . 'payment_model',
					'taxonomy'       	=> 'event_payment_model',
					'show_option_none' 	=> false,					
					'type'           	=> 'taxonomy_select',
					'remove_default' => 'true', 
					'query_args' => array(
					),
				) );

		$cmb = \new_cmb2_box( array(
			'id'           		=> $this->prefix . 'products',
			'title'        		=> __( 'Products', 'cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'low',
			//'show_on_cb'		=> function($cmb){
				//return(\has_term( 'premium', 'event_payment_model')); REPLACE WITH JS
			//},
		) );


				$cmb->add_field( array(
					'name' 				=> __( 'Linked Products', 'cmb2' ),
					'desc'             	=> 'Which products when purchased give access to this event. This has no effect is the event is set to Free',			
					'id' 				=> $this->prefix . 'products',
					'type' 				=> 'multicheck',
					'options' 			=> \liod\helpers\cmb2::get_all_od_products(),
				) );				

		
	}
	

}

