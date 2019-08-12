<?php

namespace liod\custom_post_types;

class custom_post_type{
	
	public $prefix = "liod_";
	
	/* Post Type Settings */
	public $post_type;
	public $post_type_display_name;
	public $post_type_display_name_plural;
	public $post_type_icon;
	
	public $post_type_enable_tags 				= true;
	public $post_type_enable_featured_image 	= true;
	public $post_type_enable_content 			= true;
	public $post_type_enable_title 				= true;
	public $post_type_enable_excerpt 			= true;
	public $post_type_enable_author 			= true;
	
	public $is_public							= true;
	public $show_in_nav_menus					= true;
	public $has_archive							= true;
	
	/* Holds any custom taxonomies for your post types */
	public $taxonomies = array();

	/* Removed Meta Boxes */
	public $removed_meta_boxes = array();
		
	public function __construct(){
		
		/* Load the settings for this post type from the extended setup */
		$this->setup();
		
		/* CMB2 Prefix */
		$this->prefix = $this->prefix . $this->post_type . '_'; 
		
		/* Register the new post type */
		$this->post_type_register();	

		/* Register any taxonomy for this post type */
		$this->post_type_taxonomy_register();
		
		/* Add any registered meta */
		add_action( 'cmb2_init', array($this,'post_type_meta'));
			
		/* Remove any meta Boxes */		
		add_action( 'do_meta_boxes', array($this,'remove_meta_boxes'));
		
	}
	
	/* used to setup the post type by extending */
	public function setup(){}

	/* Construct a new Taxonomy for this post type */
	public function add_taxonomy($taxonomy, $arr){
		
		$this->taxonomies[$taxonomy] = $arr;
			
	}
	
	/* Register the Post Type */
	private function post_type_register(){
		
		$taxonomies = array();
		$supports = array();
		
		if($this->post_type_enable_tags ) 				array_push($taxonomies,'post_tag');			
		if($this->post_type_enable_tags ) 				array_push($supports,'tags');	
		if($this->post_type_enable_content ) 			array_push($supports,'editor');			
		if($this->post_type_enable_featured_image ) 	array_push($supports,'thumbnail');	
		if($this->post_type_enable_title)				array_push($supports, 'title');	
		if($this->post_type_enable_excerpt)				array_push($supports, 'excerpt');				
		if($this->post_type_enable_author)				array_push($supports, 'author');	
		
		if(!$this->post_type_enable_excerpt) $this->remove_meta_box('postexcerpt');
		
		foreach($this->taxonomies as $taxonomy){
			array_push($taxonomies,$taxonomy['taxonomy']);
		}
		
		register_post_type( strtolower($this->post_type),
			array(
				'labels' => array(
					'name' => __( ($this->post_type_display_name_plural) ),
					'singular_name' => __( ($this->post_type_display_name) ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New ' . ($this->post_type_display_name) ),
					'edit_item' => __( 'Edit ' . ($this->post_type_display_name) ),
					'new_item' => __( 'Add New ' . ($this->post_type_display_name) ),
					'view_item' => __( 'View ' . ($this->post_type_display_name) ),
					'search_items' => __( 'Search ' . ($this->post_type_display_name_plural) ),
					'not_found' => __( 'No ' . ($this->post_type_display_name_plural) . ' Found' ),
					'not_found_in_trash' => __( 'No ' . ($this->post_type_display_name_plural) . ' found in trash' )
				),
				'public' => (isset($this->is_public) && $this->is_public ? true : false),
				'has_archive' => (isset($this->has_archive) && $this->has_archive ? true : false),
				'show_in_rest' => TRUE,
				'menu_icon' => $this->post_type_icon,
				'taxonomies' => $taxonomies, 		
				'show_in_nav_menus' => (isset($this->show_in_nav_menus) && $this->show_in_nav_menus ? true : false),
				'supports' => $supports,
				'menu_position' => 5,
			)
		);
		

	}
	
	private function post_type_taxonomy_register(){
		
		if(!empty($this->taxonomies)){
			
			foreach($this->taxonomies as $taxonomy){
		
				register_taxonomy($taxonomy['taxonomy'], $this->post_type, array(
					'hierarchical' => true,
					'labels' => array(
						'name' => _x( $taxonomy['taxonomy_display_name_plural'], 'taxonomy general name' ),
						'singular_name' => _x( $taxonomy['taxonomy_display_name'], 'taxonomy singular name' ),
						'search_items' =>  __( 'Search ' . $taxonomy['taxonomy_display_name_plural'] ),
						'all_items' => __( 'All ' . $taxonomy['taxonomy_display_name_plural'] ),
						'parent_item' => __( 'Parent ' . $taxonomy['taxonomy_display_name'] ),
						'parent_item_colon' => __( 'Parent ' . $taxonomy['taxonomy_display_name'] ),
						'edit_item' => __( 'Edit ' .$taxonomy['taxonomy_display_name'] ),
						'update_item' => __( 'Update ' . $taxonomy['taxonomy_display_name'] ),
						'add_new_item' => __( 'Add ' . $taxonomy['taxonomy_display_name'] ),
						'new_item_name' => __( 'New ' . $taxonomy['taxonomy_display_name'] ),
						'menu_name' => __( $taxonomy['taxonomy_display_name_plural'] ),
					),

					'rewrite' => array(
						'slug' => $taxonomy['taxonomy'], 
						'with_front' => false, 
						'hierarchical' => true 
					),
					'capabilities' => array(
						'manage_terms'  => (isset($taxonomy['taxonomy_allow_new']) && $taxonomy['taxonomy_allow_new'] == false ? 'null' : 'edit_posts'),
						'edit_terms'    => (isset($taxonomy['taxonomy_allow_new']) && $taxonomy['taxonomy_allow_new'] == false ? 'null' : 'edit_posts'),
						'delete_terms'  => (isset($taxonomy['taxonomy_allow_new']) && $taxonomy['taxonomy_allow_new'] == false ? 'null' : 'edit_posts'),
						'assign_terms'  => (isset($taxonomy['taxonomy_allow_new']) && $taxonomy['taxonomy_allow_new'] == false ? 'null' : 'edit_posts'),			
					)
				));
			
				if(!empty($taxonomy['taxonomy_terms'])){
					
					$all_terms = get_terms( 
						 $taxonomy['taxonomy'],
						 array(
							 'hide_empty'   => false,
							 'taxonomy'     => $taxonomy['taxonomy']
						) 
					);
					
					
					/* This adds the predefined terms if they are not already created */
					foreach($taxonomy['taxonomy_terms'] as $taxonomy_term){
						if(!term_exists( $taxonomy_term['display_name'], $taxonomy['taxonomy'] )){
							wp_insert_term( $taxonomy_term['display_name'], $taxonomy['taxonomy'], array('description'=> $taxonomy_term['display_name'], 'slug' => $taxonomy_term['term']) );
						}
					}	
		
				}

			}
		
		}
		
	}
	
	/* Adds a meta box to remove from admin post screen */
	public function remove_meta_box($id){
		if(is_array($id)){
		
			$this->removed_meta_boxes = array_merge($id,$this->removed_meta_boxes);	
			
		}else{
			$this->removed_meta_boxes[] = $id;
		}
		
	}
	
	/* Called During to actually remove the boxes defined by remove meta box */
	public function remove_meta_boxes(){
		
		if(!empty($this->removed_meta_boxes)){
			
			foreach($this->removed_meta_boxes as $meta_box_id){
				\remove_meta_box( $meta_box_id, $this->post_type, 'normal' );
			}
		}
		
	}
	
}
	