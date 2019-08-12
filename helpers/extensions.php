<?php

/* Extender for WP Query to include meta */
if(!function_exists('add_query_meta')) {
	function add_query_meta($wp_query = "") {

		//return In case if wp_query is empty or postmeta already exist
		if( (empty($wp_query)) || (!empty($wp_query) && !empty($wp_query->posts) && isset($wp_query->posts[0]->postmeta)) ) { return $wp_query; }

		$sql = $postmeta = '';
		$post_ids = array();
		$post_ids = wp_list_pluck( $wp_query->posts, 'ID' );
		
		if(!empty($post_ids)) {
			global $wpdb;
			$post_ids = implode(',', $post_ids);
			$sql = "SELECT meta_key, meta_value, post_id FROM $wpdb->postmeta WHERE post_id IN ($post_ids)";
			$postmeta = $wpdb->get_results($sql, OBJECT);
			if(!empty($postmeta)) {
				foreach($wp_query->posts as $pKey => $pVal) {
					$wp_query->posts[$pKey]->postmeta = new StdClass();
					foreach($postmeta as $mKey => $mVal) {
		
						if(isset($mVal->meta_value)){
							$nmKey = $mVal->meta_key;
							if($postmeta[$mKey]->post_id == $wp_query->posts[$pKey]->ID) {
								$newmeta[$nmKey] = new stdClass();
								$newmeta[$nmKey]->meta_key = $postmeta[$mKey]->meta_key;
								$newmeta[$nmKey]->meta_value = maybe_unserialize($postmeta[$mKey]->meta_value);
								$wp_query->posts[$pKey]->postmeta = (object) array_merge((array) $wp_query->posts[$pKey]->postmeta, (array) $newmeta);
								unset($newmeta);
							}
						}
					}
				
				}
			}
			unset($post_ids); unset($sql); unset($postmeta);
		}
		return $wp_query;
	}
}

/* Wrapper for WP Query and Above for getting all meta and posts, see read me */
if(!function_exists('get_posts_with_meta')) {	

	function get_posts_with_meta($args){
		
		global $wp_preserve_query;
		
		if(!isset($wp_preserve_query) || $wp_preserve_query == false){
			global $wp_query;
			unset($wp_preserve_query);
		}			
		
		$wp_query = new \WP_Query( $args );
		
	
		if($wp_query->have_posts()) {
			$wp_query = add_query_meta($wp_query);
			return $wp_query->posts;
		}
	
	}

}

/* Extension of wc_has_customer_bought_product to return the most recent order they purchased it */
if(!function_exists('wc_order_where_customer_bought_product')) {	

	function wc_order_where_customer_bought_product( $customer_email, $user_id, $product_id ) {
		global $wpdb;

		$result = apply_filters( 'woocommerce_pre_customer_bought_product', null, $customer_email, $user_id, $product_id );

		if ( null !== $result ) {
			return $result;
		}

		$transient_name    = 'wc_order_where_customer_bought_product_' . md5( $customer_email . $user_id );
		$transient_version = WC_Cache_Helper::get_transient_version( 'orders' );
		$transient_value   = get_transient( $transient_name );

		if ( isset( $transient_value['value'], $transient_value['version'] ) && $transient_value['version'] === $transient_version ) {
			$result = $transient_value['value'];
		} else {
			$customer_data = array( $user_id );

			if ( $user_id ) {
				$user = get_user_by( 'id', $user_id );

				if ( isset( $user->user_email ) ) {
					$customer_data[] = $user->user_email;
				}
			}

			if ( is_email( $customer_email ) ) {
				$customer_data[] = $customer_email;
			}

			$customer_data = array_map( 'esc_sql', array_filter( array_unique( $customer_data ) ) );
			$statuses      = array_map( 'esc_sql', wc_get_is_paid_statuses() );

			if ( count( $customer_data ) === 0 ) {
				return false;
			}

			$result = $wpdb->get_col(
				"
				SELECT i.order_id FROM {$wpdb->posts} AS p
				INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
				INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
				WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $statuses ) . "' )
				AND pm.meta_key IN ( '_billing_email', '_customer_user' )
				AND im.meta_key IN ( '_product_id', '_variation_id' )
				AND im.meta_value != 0
				AND pm.meta_value IN ( '" . implode( "','", $customer_data ) . "' )
			"
			
			); // WPCS: unprepared SQL ok.
			
			$result = array_map( 'absint', $result );
			
			$transient_value = array(
				'version' => $transient_version,
				'value'   => $result,
			);

			set_transient( $transient_name, $transient_value, DAY_IN_SECONDS * 30 );
		}
		return empty($result) ? false : $result[0];
	}

}

if(!function_exists("wp_next_is_global")){


	
}
