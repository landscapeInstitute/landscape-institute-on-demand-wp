<?php

namespace liod\custom_product_types;

class custom_product_type{
	
	public $prefix = "liod_";
	
	/* Product Type Settings */
	public $product_type;
	public $product_type_display_name;
	public $product_type_display_name_plural;
	

	public function __construct(){
		
		$this->setup();
		
		$this->product_type_register();
		
		add_filter( 'product_type_selector', array($this,'product_type_selector'));
	
		add_filter( 'woocommerce_product_data_tabs', array($this,'product_tab') );
		
		add_action( 'woocommerce_product_data_panels', array($this,'product_tab_content' ) );
			
		add_action( 'admin_footer', array($this,'product_tab_custom_js') );	
		
		add_action( 'woocommerce_process_product_meta', array($this,'product_tab_data_save') );
			
	}
	
	

	function product_tab_data_save( $post_id ){
			
	
	}	
		
	
	public function product_type_selector( $types ){
		
		$types[ $this->product_type ] = __( $this->product_type_display_name, $this->product_type . '_product' );

		return $types;
	}
	
	public function product_tab( $tabs ){
		
		return $tabs;
			
	}
	
	public function product_tab_content(){
		
	}	
	
	/* Used to setup the product type by extending */
	public function setup(){}
	
	/* Ensures the General Pricing Information Tab is shown when it should be */
	public function product_tab_custom_js(){
		
		if ( 'product' != get_post_type() ) :
				return;
			endif;
			?><script type='text/javascript'>
				jQuery( document ).ready( function() {
					jQuery( '.options_group.pricing' ).addClass( 'show_if_<?php echo $this->product_type ?>' ).show();
				});
			</script><?php
    }
	
	/* Register the Product Type */
	private function product_type_register(){
		
		$fly_by_class = '
		
		class WC_Product_' . ucwords($this->product_type) . ' extends \WC_Product {
			
			public function __construct( $product ) {
				
				$this->product_type = "' . $this->product_type . '";
				parent::__construct( $product );
				
			}	
		}
		';
		
		eval($fly_by_class);

	}
	
}


	