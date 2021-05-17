<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'MywpDeveloperAbstractModule' ) ) {
  return false;
}

if ( ! class_exists( 'MywpDeveloperModuleDevConsole' ) ) :

final class MywpDeveloperModuleDevConsole extends MywpDeveloperAbstractModule {

  static protected $id = 'dev_console';

  protected static function after_init() {

    add_action( 'mywp_wp_loaded' , array( __CLASS__ , 'mywp_wp_loaded' ) );

  }

  public static function mywp_debug_renders( $debug_renders ) {

    $debug_renders[ self::$id . '_log' ] = array(
      'debug_type' => 'console',
      'title' => 'console.log',
    );

    $debug_renders[ self::$id . '_info' ] = array(
      'debug_type' => 'console',
      'title' => 'console.info',
    );

    $debug_renders[ self::$id . '_error' ] = array(
      'debug_type' => 'console',
      'title' => 'console.error',
    );

    $debug_renders[ self::$id . '_warn' ] = array(
      'debug_type' => 'console',
      'title' => 'console.warn',
    );

    return $debug_renders;

  }

  public static function mywp_wp_loaded() {

    add_action( 'mywp_debug_render_' . self::$id . '_log' , array( __CLASS__ , 'mywp_debug_render_log' ) );

    add_action( 'mywp_debug_render_' . self::$id . '_info' , array( __CLASS__ , 'mywp_debug_render_info' ) );

    add_action( 'mywp_debug_render_' . self::$id . '_error' , array( __CLASS__ , 'mywp_debug_render_error' ) );

    add_action( 'mywp_debug_render_' . self::$id . '_warn' , array( __CLASS__ , 'mywp_debug_render_warn' ) );

    add_action( 'mywp_debug_render_footer' , array( __CLASS__ , 'mywp_debug_render_footer' ) );

  }

  private static function print_debug_textarea( $id ) {

    printf( '<textarea id="mywp-debug-console-%s"></textarea>' , esc_attr( $id ) );

  }

  public static function mywp_debug_render_log() {

    self::print_debug_textarea( 'log' );

  }

  public static function mywp_debug_render_info() {

    self::print_debug_textarea( 'info' );

  }

  public static function mywp_debug_render_error() {

    self::print_debug_textarea( 'error' );

  }

  public static function mywp_debug_render_warn() {

    self::print_debug_textarea( 'warn' );

  }

  public static function mywp_debug_render_footer() {

    ?>
    <script>
    ( function() {

      const mywp_console_original = console;

      if( typeof console !== 'object' ) {

        return false;

      }

      function mywp_console_print( console_type , content ) {

        const current_el = document.getElementById( 'mywp-debug-console-' + console_type );

        const current_content = current_el.value;

        const current_date = new Date();

        const format_date = current_date.toString();

        let replace_content = '';

        if( typeof content === 'object' ) {

          replace_content = JSON.stringify( content );

        } else {

          replace_content = content;

        }

        let new_content = current_content + format_date + ' ' + replace_content + "\n";

        current_el.textContent = new_content;

      }

      console.log = function( content ) {

        mywp_console_print( 'log' , content );

      }

      console.info = function( content ) {

        mywp_console_print( 'info' , content );

      }

      console.error = function( content ) {

        mywp_console_print( 'error' , content );

      }

      console.warn = function( content ) {

        mywp_console_print( 'warn' , content );

      }

    } )();
    </script>
    <?php

  }

}

MywpDeveloperModuleDevConsole::init();

endif;
