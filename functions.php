<?php

/*
Plugin Name: Landscape Institute | On-Demand WP
Plugin URI: https://github.com/landscapeInstitute/landscape-institute-on-demand-wp
Description: Provide OnDemand services via events, videos, purchases and subscriptions
Version: 1.5
Author: Louis Varley
Author URI: http://www.landscapeinstitute.org
*/
/*
	Copyright 2013	Landscape Institute	(email : louis.varley@landscapeinstitute.org)
	Licensed under the GPLv2 license: http://www.gnu.org/licenses/gpl-2.0.html
*/

/* Defines */
define('LIOD_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('LIOD_PLUGIN_PATH',plugin_dir_path(__FILE__));
define('LIOD_CLASSES_PATH',LIOD_PLUGIN_PATH . 'classes/');

/* Include Composer */
require(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

add_action('admin_init',function(){
	new WP_GitHub_Updater(__FILE__);
});

/* CMB2 */
require_once(LIOD_PLUGIN_PATH . 'cmb2.php');

/* Helpers */
require_once(LIOD_PLUGIN_PATH . 'helpers/functions.php');
require_once(LIOD_PLUGIN_PATH . 'helpers/extensions.php');

/* Composer */
$composer = require_once(LIOD_PLUGIN_PATH . 'vendor/autoload.php');

/* Plugin Init */
add_action('init',function(){
	liod()->init();
    do_action('liod_init');
});

/* Admin Init */
add_action('admin_init',function(){
	liod()->admin_init();
    do_action('liod_admin_init');
});


/* Helper function for getting the main instance */
function liod(){	
	return \liod\core\core::instance();
}
