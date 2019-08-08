<?php

namespace liod\custom_product_types;

class subscription extends \liod\custom_product_types\custom_product_type{
	
	public function setup(){
		
		$this->product_type = 'subscription';
		$this->product_type_display_name = 'Subscription';
		$this->product_type_display_name_plural = 'Subscriptions';
		
	}
	
	
	/* Remove these tabs */
	public function product_tab( $tabs ){
		
		unset( $tabs['shipping'] );
		unset( $tabs['linked_product'] );
		unset( $tabs['variations'] );			
		unset( $tabs['attribute'] );

		$tabs['subscription'] = array(
		  'label'	 => __( 'Subscription', 'liod' ),
		  'target' => 'subscription_options',
		  'class'  => 'show_if_subscription',
		 );

		return $tabs;
			
	}
	

	
	function product_tab_data_save( $post_id ){
			
		$subscription_duration = $_POST['subscription_duration'];
			
		if( !empty( $subscription_duration ) ) {
			update_post_meta( $post_id, 'subscription_duration', esc_attr( $subscription_duration ) );
		}else{
			update_post_meta( $post_id, 'subscription_duration', 0 );
		}
	}		
	
	public function product_tab_content(){
		
		?><div id='subscription_options' class='panel woocommerce_options_panel'><?php
		?><div class='options_group'><?php
				
		woocommerce_wp_text_input(
		array(
		  'id' => 'subscription_duration',
		  'label' => __( 'Duration', 'liod' ),
		  'placeholder' => 'Days this subscription is valid.',
		  'desc_tip' => 'true',
		  'description' => __( 'How many days is this subscription valid for. blank means this will never expire', 'liod' ),
		  'type' => 'text'
		)
		);
		?></div>
		</div><?php
	}		
	
}