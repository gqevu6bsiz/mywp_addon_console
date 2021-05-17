<?php
/*
Plugin Name: My WP Add-on Console
Plugin URI: https://mywpcustomize.com/add_ons/my-wp-add-on-console/‎
Description: My WP Add-on Console is debug to console for Developer.
Version: 1.0.0
Author: gqevu6bsiz
Author URI: https://mywpcustomize.com/
Text Domain: mywp-console
Domain Path: /languages
My WP Test working: 1.17.0
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'MywpConsole' ) ) :

final class MywpConsole {

  private static $instance;

  private function __construct() {}

  public static function get_instance() {

    if ( !isset( self::$instance ) ) {

      self::$instance = new self();

    }

    return self::$instance;

  }

  private function __clone() {}

  private function __wakeup() {}

  public static function init() {

    self::define_constants();
    self::include_core();

    add_action( 'mywp_start' , array( __CLASS__ , 'mywp_start' ) );

  }

  private static function define_constants() {

    define( 'MYWP_CONSOLE_NAME' , 'My WP Add-On Console' );
    define( 'MYWP_CONSOLE_VERSION' , '1.0.0' );
    define( 'MYWP_CONSOLE_PLUGIN_FILE' , __FILE__ );
    define( 'MYWP_CONSOLE_PLUGIN_BASENAME' , plugin_basename( MYWP_CONSOLE_PLUGIN_FILE ) );
    define( 'MYWP_CONSOLE_PLUGIN_DIRNAME' , dirname( MYWP_CONSOLE_PLUGIN_BASENAME ) );
    define( 'MYWP_CONSOLE_PLUGIN_PATH' , plugin_dir_path( MYWP_CONSOLE_PLUGIN_FILE ) );
    define( 'MYWP_CONSOLE_PLUGIN_URL' , plugin_dir_url( MYWP_CONSOLE_PLUGIN_FILE ) );

  }

  private static function include_core() {

    $dir = MYWP_CONSOLE_PLUGIN_PATH . 'core/';

    require_once( $dir . 'class.api.php' );

  }

  public static function mywp_start() {

    add_action( 'mywp_plugins_loaded', array( __CLASS__ , 'mywp_plugins_loaded' ) );

    add_action( 'init' , array( __CLASS__ , 'wp_init' ) );

  }

  public static function mywp_plugins_loaded() {

    add_filter( 'mywp_controller_plugins_loaded_include_modules' , array( __CLASS__ , 'mywp_controller_plugins_loaded_include_modules' ) );

    add_filter( 'mywp_developer_plugins_loaded_include_modules' , array( __CLASS__ , 'mywp_developer_plugins_loaded_include_modules' ) );

    add_filter( 'mywp_debug_types' , array( __CLASS__ , 'mywp_debug_types' ) );

  }

  public static function wp_init() {

    load_plugin_textdomain( 'mywp-console' , false , MYWP_CONSOLE_PLUGIN_DIRNAME . '/languages' );

  }

  public static function mywp_controller_plugins_loaded_include_modules( $includes ) {

    $dir = MYWP_CONSOLE_PLUGIN_PATH . 'controller/modules/';

    $includes['console_main_general'] = $dir . 'mywp.controller.module.main.general.php';
    $includes['console_updater']      = $dir . 'mywp.controller.module.updater.php';

    return $includes;

  }

  public static function mywp_developer_plugins_loaded_include_modules( $includes ) {

    $dir = MYWP_CONSOLE_PLUGIN_PATH . 'developer/modules/';

    $includes['console_developer'] = $dir . 'mywp.developer.module.console.php';

    return $includes;

  }

  public static function mywp_debug_types( $debug_types ) {

    $debug_types['console'] = 'Console';

    return $debug_types;

  }

}

MywpConsole::init();

endif;
