<?php

/**
 * Plugin Name:       WP Youtube Playlists
 * Plugin URI:        https://github.com/httFox/wp-youtube-playlist
 * Description:       WordPress plugin that creates and manages playlists using shortcodes
 * Version:           0.1
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Pedro Figueira
 * Author URI:        https://pedrofigueira.dev/
 */




// Access is't per wp?
defined( 'WPINC' ) OR die( 'This script cannot be accessed directly.' );
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );


// Define constants
if( !defined( 'HTTFOX_WYP_VERSION' ) ){
  define( 'HTTFOX_WYP_VERSION', '0.1' );
}

if( !defined( 'HTTFOX_WYP_NAME' ) ){
  define( 'HTTFOX_WYP_NAME', 'WP Youtube Playlist' );
}

if( !defined( 'HTTFOX_WYP_SLUG' ) ){
  define( 'HTTFOX_WYP_SLUG', 'httfox-wp-youtube-playlists' );
}

if( !defined( 'HTTFOX_WYP_SLUG_DB' ) ){
  define( 'HTTFOX_WYP_SLUG_DB', 'httfox_wp_youtube_playlists' );
}

if( !defined( 'HTTFOX_WYP_BASENAME' ) ){
  define( 'HTTFOX_WYP_BASENAME', plugin_basename( __FILE__ ) );
}

if( !defined( 'HTTFOX_WYP_DIR' ) ){
  define( 'HTTFOX_WYP_DIR', plugin_dir_path( __FILE__ ) );
}

if( !defined( 'HTTFOX_WYP_DIR_URL' ) ){
  define( 'HTTFOX_WYP_DIR_URL', plugin_dir_url( __FILE__ ) );
}


// Config admin
if( is_admin() ){
  // Function to add the link directing to the settings in the "plugins" tab
  require_once HTTFOX_WYP_DIR . '/includes/config/settings-link.php';
  
  // Create admin menu
  require_once HTTFOX_WYP_DIR . '/includes/config/create-admin-menu.php';
}

?>