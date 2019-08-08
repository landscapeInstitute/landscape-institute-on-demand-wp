<?php

namespace liod\custom_product_types;

class event extends \liod\custom_product_types\custom_product_type{
	
	public function setup(){
		
		$this->product_type = 'event';
		$this->product_type_display_name = 'Event';
		$this->product_type_display_name_plural = 'Events';
		
	}
	
	
	/* Remove these tabs */
	public function product_tab( $tabs ){
		
		unset( $tabs['shipping'] );
		unset( $tabs['linked_product'] );
		unset( $tabs['variations'] );			
		unset( $tabs['attribute'] );		
		return $tabs;
			
	}
	
	
	
}