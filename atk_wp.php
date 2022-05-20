<?php

/*
Plugin Name: ATK WordPress
Description: WordPress starter plugin that uses ATK UI
Version: 1.0
Author: Sam Zielke-Ryner
Author URI: https://visualdesigner.io
*/

global $wp_version;
$php_version = defined( 'PHP_VERSION' ) ? PHP_VERSION : ( function_exists( 'phpversion' ) ? phpversion() : '' );
if ( version_compare( $php_version, '7.6', '<' ) || version_compare( $wp_version, '5.0', '<' ) ) {
	error_log( '****** ATK WordPress requires PHP version 7.6 or above and WordPress version 5.0 or above. ******' );
	function atk_wp_nag() {
		global $wp_version;
		$php_version = defined( 'PHP_VERSION' ) ? PHP_VERSION : ( function_exists( 'phpversion' ) ? phpversion() : '' );
		$class       = 'notice notice-error is-dismissible';
		$message     = "ATK WordPress requires PHP version 7.6 or above and WordPress version 5.0 or above. You have PHP $php_version and WordPress $wp_version";

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
	add_action( 'admin_notices', 'atk_wp_nag' );
	return;
}

// namespace atk_wp;

require 'vendor/autoload.php';


new \atk_wp\Page_Example( 'Page_Example' );

