<?php

/**
 * LIOD Core
 *
 * Core LIOD Class and Methods
 *
 * @package LIOD
 * @subpackage Core
 */
/**

/**
 * Transients Names Used
 * @transients_name 	liod_purchased_products 
 * @transients_value 	All Purchased LIOD products
 * @transients_life 	12 hours
 * 	
 * @transients_name 	liod_purchased_events
 * @transients_value 	All Purchased LIOD events
 * @transients_life 	12 hours
 * 	
 */

namespace liod\core;

class core{
	
	protected static $instance = null;
	
	public $custom_post_types = [];
	public $custom_product_types = [];


	/**
	 * Returns the current instance of LIOD Core
	 * 
	 * @return CLASS INSTANCE
	 */ 
    public static function instance() {

        if ( null == static::$instance ) {
            static::$instance = new static();
        }

        return static::$instance;
    }

	/**
	 * Called on initialisation
	 *
	 */ 	
	public function init(){
		
		/* Custom Post Types */
		$this->custom_post_types['video'] = new \liod\custom_post_types\video();
		$this->custom_post_types['event'] = new \liod\custom_post_types\event();
		
		/* Custom Product Types */
		$this->custom_product_types['event'] = new \liod\custom_product_types\event();
		$this->custom_product_types['subscription'] = new \liod\custom_product_types\subscription();	

		/* Hooks which control when transients should be cleared */
		$this->transient_clear_hooks();
	
	}
	
	
	/**
	 * Called on admin initialisation
	 *
	 */ 	
	public function admin_init(){

	}
	
	/**
	 * Returns the name to be used for a user transient
	 *
	 * @param name   	$name Transient name
	 * @param user_id   $user_id An Optional user_id otherwise current user is used
	 * 
	 * @return STRING
	 */ 	
	public function get_user_transient_name( $name, $user_id = null ){
		
		if(empty($user_id)) $user_id =  wp_get_current_user()->ID;
		
		return $name . '_' . md5( $user_id );
		
	}
	
	/**
	 * Get all Events as WP_POSTS
	 *
	 * @param name   	$name Transient name
	 * @param user_id   $user_id An Optional user_id otherwise current user is used
	 *
	 */ 		
	public function clear_transient( $name, $user_id = null ){
		
		$transient_name = get_user_transient_name( $name, $user_id );
		
		delete_transient($transient_name);
		
	}
	

	
	/**
	 * All Events which require certain transients to be cleared
	 *
	 */ 	
	public function transient_clear_hooks(){
		
		add_action( 'woocommerce_payment_complete', function( $order_id ){  

			$order = wc_get_order( $order_id );			
			$this->clear_transient('liod_purchased_products' ,$order->user_id);
			$this->clear_transient('liod_purchased_events' ,$order->user_id);
			
		},  1, 1  );
		
	}
	
	/**
	 * Get all Events as WP_POSTS
	 *
	 * @param Arguments   $args An Optional WP Query Argument 
	 * 
	 * @return ARRAY WP_POSTS
	 */ 
	private function get_all_event_posts( $args = null ){
	
		$args = array (
			'post_type' => $this->custom_post_types['event']->post_type,
			'post_status' => 'publish',
		);

		$events = get_posts_with_meta($args);
		
		return $events;
		
	}

	/**
	 * Get all Videos as WP_POSTS
	 *
	 * @param Arguments   $args An Optional WP Query Argument 
	 * 
	 * @return ARRAY WP_POSTS
	 */ 
	private function get_all_video_posts( $event_id = null, $args = array() ){
	
		$args = array_merge($args, array (
			'post_type' => $this->custom_post_types['video']->post_type,
			'post_status' => 'publish',
		));
		
		if(!empty($event_id)){
			
			$args = array_merge($args,
				array(
					'meta_query' => array(
						array(
							'key' => 'liod_video_event',
							'value' => $event_id,
							'compare' => '=',
					   )
					)
				)
			);
		}
		
		$videos = get_posts_with_meta($args);
		
		return $videos;
		
	}	
	
	/**
	 * Get a single Event as WP_POST
	 *
	 * @param post_id    $post_id the event post
	 * @param Arguments  $args An Optional WP Query Argument 
	 * 
	 * @return WP_POST
	 */ 
	private function get_event_post( $post_id, $args = array() ){
	
		$args = array_merge($args, array (
			'p'				=> $post_id,
			'post_type' 	=> $this->custom_post_types['event']->post_type,
			'post_status' 	=> 'publish',
		));

		$events = get_posts_with_meta($args);
		
		return reset($events);
		
	}
	
	/**
	 * Get a single Video as WP_POST
	 *
	 * @param post_id     $post_id for the video post	 
	 * @param Arguments   $args An Optional WP Query Argument 
	 * 
	 * @return WP_POST
	 */ 
	private function get_video_post( $post_id, $args = array() ){
	
		$args = array_merge($args, array (
			'p'				=> $post_id,
			'post_type' 	=> $this->custom_post_types['video']->post_type,
			'post_status' 	=> 'publish',
		));

		$videos = get_posts_with_meta($args);
		
		return reset($videos);
		
	}	
	
	/**
	 * Get all events as EVENT
	 *
	 * @param Arguments   $args An Optional WP Query Argument 
	 * 
	 * @return ARRAY EVENTS
	 */ 
	public function get_all_events( $args = array() ){
		
		$event_posts = $this->get_all_event_posts();
		$events = [];
		
		foreach($event_posts as $key => $event_post){
			
			if($this->has_purchased_event($event_post->ID)) $event_posts[$key]->purchased = true;
			$events[] = new \liod\core\event($event_post);

		}
		
		return $events;
		
	}
	
	/**
	 * Get all events as VIDEOS
	 *
	 * @param Arguments   $event_id An Optional event_id 
	 * @param Arguments   $args An Optional WP Query Argument 
	 * 
	 * @return ARRAY VIDEOS
	 */ 
	public function get_all_videos( $event_id = null, $args = array() ){
		
		$video_posts = $this->get_all_video_posts( $event_id, $args );
		$videos = [];
		
		foreach($video_posts as $key => $video_post){
			
			$videos[] = new \liod\core\video($video_post);

		}
		
		return $events;
		
	}	
	
	/**
	 * Get a single Event as EVENT
	 *
	 * @param event_id     $event_id for the event	 
	 * 
	 * @return EVENT
	 */
	public function get_event( $event_id ){
		
		$event_post = $this->get_all_event_posts( $event_id );
		
		return new \liod\core\event(reset($event_post));
		
	}
	
	/**
	 * Get a single Video as VIDEO
	 *
	 * @param video_id     $video_id for the video	 
	 * 
	 * @return VIDEO
	 */
	public function get_video( $video_id ){
		
		$video_post = $this->get_all_video_posts( $video_id );
		
		return new \liod\core\video(reset($video_post));
		
	}	
	
	/**
	 * Get All Event Products
	 * 
	 * @return ARRAY WC_PRODUCTS
	 */
	public function get_all_event_products(){
		
		$args = array(
			'type' => 'event',
		);
		$products = wc_get_products( $args );
		return $products;
 
	}
	
	/**
	 * Get All Subscription Products
	 * 
	 * @return ARRAY WC_PRODUCTS
	 */
	public function get_all_subscription_products(){
		
		$args = array(
			'type' => 'subscription',
		);
		$products = wc_get_products( $args );
		return $products;
 
	}
		

	/**
	 * Get All Products the given user has purchased
	 * 
	 * @param user_id     $user_id optional user_id otherwise current is used		
	 * 	 
	 * @return ARRAY WC_PRODUCTS
	 */
	public function get_purchased_products( $user_id = null ){

		global $wpdb;
		
		if(empty($user_id)){
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
		}
		
		$products = [];
		
		/* Not logged in */
		if ( 0 == $user_id ) return;
		
		$user = get_userdata($user_id);
		
		$transient_name    = $this->get_user_transient_name('liod_purchased_products');
		$transient_value   = get_transient( $transient_name );
		
		if ( isset( $transient_value['value'] ) ) {
			
			$products = $transient_value['value'];
			
		}else{
		
			$all_subscription_products = $this->get_all_subscription_products();
			$all_event_products = $this->get_all_event_products();
			
			/* If admin or editor, this gives access to all products */
			if( current_user_can('editor') || current_user_can('administrator') ) {		
				$products = array_merge($products, $all_subscription_products, $all_event_products);
				
			/* Otherwise get a list of all products the user has purchased */
			}else{
				
				/* Push Subscription products purchased if valid */
				foreach($all_subscription_products as $product){

					$order_id = wc_order_where_customer_bought_product( $user->user_email, $user->ID, $product->get_id() );
					
					if($order_id){
						$order = wc_get_order($order_id);
						
						$order_date = $order->get_date_created();
						$subscription_valid = $product->get_meta('subscription_duration');
						
						/* If Subscription purchased is within it's allowed timespan */
						if (strtotime($order_date) >= strtotime('-' . $subscription_valid . ' days'))
							array_push($products,$product); 
					}

				}

				/* Push event products purchased */
				foreach($all_event_products as $product){
					if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->get_id() ) ) array_push($products,$product); 
				}
				
				$products = array_unique( $products );
				
				$transient_value = array(
					'value'   => $products
				);			 
				 
				set_transient( $transient_name, $transient_value, 12 * HOUR_IN_SECONDS );
				
			
			}
		
		}
		
		return $products;

	}		
		
	/**
	 * Get all events as EVENT which a user has purchased
	 *
	 * @param user_id     $user_id optional user_id otherwise current is used 
	 * 
	 * @return ARRAY EVENTS
	 */ 
	public function get_purchased_events($user_id = null){
		
		if(empty($user_id)) $user_id =  wp_get_current_user()->ID;
		
		$transient_name    = $this->get_user_transient_name('liod_purchased_events');
		$transient_value   = get_transient( $transient_name );
		
		if ( isset( $transient_value['value'] ) ) {
			
			$this->purchased_events = $transient_value['value'];
			
		}else{
				
			if(isset($this->purchased_events)){
				return $this->purchased_events;
			}
			
			$products = $this->get_purchased_products();
			
			/* No Purchase of Products */
			if(empty($products)) return;
			
			/* Loops through purchased product, Subscriptions return all but events get pushed into array */
			foreach($products as $product){
				
				if(is_a($product,'WC_Product_Subscription')){
					$this->purchased_events = $this->get_all_event_posts();
					return $this->purchased_events;
				}
				
				if(is_a($product,'WC_Product_Event')){
					$product_ids[] = $product->get_id();
				}			
			}
			
			$query_str = "
				SELECT $wpdb->posts.* 
				FROM $wpdb->posts, $wpdb->postmeta
				WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
				AND $wpdb->postmeta.meta_key = '" . $this->custom_post_types['event']->prefix . "products'
				AND (
				";
				
				foreach($product_ids as $product_id){
					 $query_str .= "$wpdb->postmeta.meta_value like '%\"" . $product_id . "\"%' or";
				}
				
				$query_str = rtrim($querystr,'or');			
				$query_str .= ")
				
				AND $wpdb->posts.post_status = 'publish' 
				AND $wpdb->posts.post_type = '" . $this->custom_post_types['event']->post_type . "'
				AND $wpdb->posts.post_date < NOW()
				ORDER BY $wpdb->posts.post_date DESC
			 ";
			 
			$this->purchased_events = $wpdb->get_results($query_str, OBJECT);
			 
			$transient_value = array(
				'value'   => $this->purchased_events
			);			 
			 
			set_transient( $transient_name, $transient_value, 12 * HOUR_IN_SECONDS );
						 
		}

		return $this->purchased_events;
		
	}
	
	/**
	 * has the current user purchased a given event
	 *
	 * @param event_id    $event_id the event to check for purchase of
	 * @param user_id     $user_id optional user_id otherwise current is used 
	 * 
	 * @return BOOL
	 */ 
	public function has_purchased_event( $event_id, $user_id = null ){
		
		$purchased_events = $this->get_purchased_events( $user_id );

		foreach($purchased_events as $purchased_event){
			if($purchased_event->ID == $event_id) return true;
		}
		
		return false;
		
	}
	
	
	/**
	 * Get a single Event as EVENT from a video ID
	 *
	 * @param video_id     $video_id to find attached event	 
	 * 
	 * @return EVENT
	 */	
	public function get_event_from_video( $video_id ){
		
		$video = $this->get_video();
		
		if(!empty($event->post_meta->liod_video_event)){
			return $this->get_event($video->post_meta->liod_video_event);
		}
		
	}
	
	/**
	 * Get all event products as ARRAY of WC_PRODUCT from event_id
	 *
	 * @param event_id     $event_id to find product 
	 * 
	 * @return ARRAY WC_PRODUCT
	 */	
	public function get_products_from_event( $event_id ){
		
		
		$events = $this->get_event( $event_id );
		$products = [];
		
		foreach($events as $event){
			
			if(!empty($event->post_meta->liod_event_products)){
				
				foreach(explode(',',$event->post_meta->liod_event_products->meta_value) as $product_id){
					
					$products[] = wc_get_products(array('ID' => $product_id) );
				}
				
			}
			
		}
		
		return $products;
		
	}	
	
}

