<?php

namespace liod\custom_post_types;

class video extends \liod\custom_post_types\custom_post_type{
		
	function setup(){
		
		$this->post_type = "video";
		$this->post_type_icon 					= 'dashicons-media-video';
		$this->post_type_display_name 			= 'Video';
		$this->post_type_display_name_plural 	= "Videos";		

		$this->post_type_enable_tags 			= false;
		$this->post_type_enable_featured_image 	= false;	
		$this->post_type_enable_content 		= false;	
		$this->post_type_enable_title 			= false;		
		$this->post_type_enable_excerpt 		= false;
		$this->post_type_enable_author 			= false;		

	}

	function post_type_meta(){
		
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
			'id'           		=> $this->prefix .'details_metabox',
			'title'        		=> __( 'Details', 'cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );

				$cmb->add_field( array(
					'name'      => 'Title',
					'desc'      => 'The title of this video.',
					'id'        => 'post_title',					
					'default' 	=> '',					
					'type'      => 'text',
				) );
		
				$cmb->add_field( array(
					'name'      => 'Summary',
					'desc'      => 'A summary about this Video Session.',
					'id'        => 'excerpt',					
					'type'      => 'textarea',
					'escape_cb' => false,
				) );	
				
				$cmb->add_field( array(
					'name'      		=> 'Description',
					'desc'      		=> 'Video Session Description',
					'id'        		=> 'content',					
					'type'      		=> 'wysiwyg',
					'escape_cb' 		=> false,
				) );					
				
				$cmb->add_field( array(
					'name' 				=> __( 'Vimeo URL', 'cmb2' ),
					'desc'             	=> 'Vimeo private video link',			
					'id' 				=> $this->prefix . 'url',
					'type' 				=> 'text_url',
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
			'id'           		=> $this->prefix . 'event_metabox',
			'title'        		=> __( 'Event', 'cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );

				$cmb->add_field( array(
					'name' 				=> __( 'Event', 'cmb2' ),
					'desc'             	=> 'Which event is linked to this video',			
					'id' 				=> $this->prefix . 'event',
					'type' 				=> 'select',
					'options' 			=> \liod\helpers\cmb2::get_all_events_as_option(),
				) );

		
		$cmb = \new_cmb2_box( array(
			'id'           		=> $this->prefix . $this->post_type . 'speaker_metabox',
			'title'        		=> __( 'Speaker','cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );		
		
				$cmb->add_field( array(
					'name' 				=> __( 'Speaker Name', 'cmb2' ),
					'desc'             	=> 'Name of the speaker in this video',	
					'id' 				=> $this->prefix . 'speaker_name',		
					'type' 				=> 'text',
				) );
						
				$cmb->add_field( array(
					'name' 				=> __( 'Speaker Bio', 'cmb2' ),
					'desc'             	=> 'Bio about the speaker in this video',	
					'id' 				=> $this->prefix . 'speaker_bio',		
					'type' 				=> 'wysiwyg',
				) );
				
				$cmb->add_field( array(
					'name'    			=> __( 'Speaker Image', 'cmb2' ),
					'desc'    			=> 'Speaker Image',
					'id' 				=> $this->prefix . 'speaker_image',
					'type'    			=> 'file',
					'options' 			=> array('url' => false),
					'text' 				=> array('add_upload_file_text' => 'Add Image'),
				) );

		$cmb = \new_cmb2_box( array(
			'id'           		=> $this->prefix . $this->post_type . 'academic_metabox',
			'title'        		=> __( 'Academic','cmb2' ),
			'object_types' 		=> array( $this->post_type ),
			'context'      		=> 'normal',
			'priority'     		=> 'high',
		) );					
				
				$cmb->add_field( array(
					'name' 				=> __( 'Learning outcomes', 'cmb2' ),
					'desc'             	=> 'What are the learning outcomes of this video',	
					'id' 				=> $this->prefix . 'learning_outcomes',		
					'type' 				=> 'wysiwyg',
				) );




	}
	
}

