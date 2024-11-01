<?php
/*
Plugin Name: autofill-CF7-BB
Plugin URI: http://www.etherocliquecite.eu
Description: Add shortcode for fields autofill of Contact Form 7 plugin by URL get variable for select, checkboxes, radio buttons, text, by Id or by value.
Author: Billyben
Version: 1.0.1
Author URI: http://asblog.etherocliquecite.eu
*/

/* inclu les utilitaire */
include_once("script/AFCFBB_shotcodefunction.php");// traitement du shortcode
include_once("script/AFCFBB_option_class.php");

$afcfbb_new_version = '1.0.0';
if (!defined('AFCFBB_TEXT_DOMAIN'))define('AFCFBB_TEXT_DOMAIN', 'afcfbb_plugin_text');

if (!defined('AFCFBB_VERSION_KEY'))define('AFCFBB_VERSION_KEY', 'afcfbb_version');
if (!defined('AFCFBB_VERSION_NUM'))define('AFCFBB_VERSION_NUM', $afcfbb_new_version);

// installation
function afcfbb_install () {
	afcfbb_check_version();
}
function afcfbb_check_version(){
	$options=get_option('afcfbb_options');
	if(!$options){
		$options=afcfbb_options::get_default_options();
		add_option('afcfbb_options', $options);
		update_option('afcfbb_options', $options);
	}
	
}
//

register_activation_hook( __FILE__, 'afcfbb_install' );
add_shortcode( 'AFCF_BB', 'afcfbb_shortcodehandler' );

add_filter('wpcf7_form_elements', 'enable_afcfbb_shortcode');

add_action('wp_loaded' , 'afcfbb_create_option_page');

function enable_afcfbb_shortcode($form){
	$form=do_shortcode($form);
	return $form;	
}

function afcfbb_create_option_page(){
	//load_plugin_textdomain( AFCFBB_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	$page=new afcfbb_options();	

}

?>