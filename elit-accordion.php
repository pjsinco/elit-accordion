<?php 
/*
Plugin Name: Elit Accordion
Plugin URI:  
Description: Shortcodes to create an accordion
Version:  0.5.1
Author: Patrick Sinco
Author URI: github.com/pjsinco
License: GPL2
*/

// if this file is called directly, abort
if (!defined('WPINC')) {
  die;
}


function elit_accordion_shortcode_init ()
{
  
  if ( ! shortcode_exists( 'accordion' ) ) {

    /**
     * Create the shortcode.
     *
     * @param array $atts     The shortcode attributes
     * @param array $content  The shortcode content
     * @return string
     */
    function elit_accordion_shortcode( $atts, $content = null ) 
    {

      elit_accordion_enqueue();

      $markup = sprintf( '%s%s%s',
                         '<div class="elit-accordion"><div class="elit-accordion__items">',
                         $content,
                         '</div></div>' );

      return do_shortcode( $markup );
    }

    function elit_accordion_enqueue()
    {
      $css_file = 'elit-accordion.min.css';
      $css_path = "public/styles/$css_file";

      wp_enqueue_style(
        'elit_accordion_styles',
        plugins_url( $css_path, __FILE__ ),
        array(),
        filemtime( plugin_dir_path(__FILE__) . "/" . $css_path ),
        'all'
      );
    }
  }
  add_shortcode( 'accordion', 'elit_accordion_shortcode' );

  if ( ! shortcode_exists( 'accordion-section' ) ) {

    /**
     * Create the shortcode.
     *
     * @param array $atts     The shortcode attributes
     * @param string $content The shortcode content
     * @return string
     */
    function elit_accordion_section_shortcode( $atts, $content = null ) 
    {

      $shortcode_atts = shortcode_atts(
        array(
          'title' => '',
          'class' => 'accordion-head',
        ),
        $atts
      );

      $markup  = '<section>';
      $markup .= '  <input type="checkbox" checked="checked">';
      $markup .= '  <i class="elit-accordion__icon"></i>';
      $markup .= '  <h2 class="' . $shortcode_atts['class'] . '">' . $shortcode_atts['title'] . '</h2>' . $content . '</section>';

      return do_shortcode( $markup );
    }
  }
  add_shortcode( 'accordion-section', 'elit_accordion_section_shortcode' );


  if ( ! shortcode_exists( 'accordion-item' ) ) {

    /**
     * Create the shortcode.
     *
     * @param array $atts     The shortcode attributes
     * @param string $content The shortcode content
     * @return string
     */
    function elit_accordion_item_shortcode( $atts, $content = null ) 
    {
      $shortcode_atts = shortcode_atts(
        array(
          'side-padding' => 'false',
          'top-padding' => 'true',
        ),
        $atts
      );

      $shortcode_atts = elit_accordion_format_atts( $shortcode_atts );

      $side_padding = $shortcode_atts['side_padding'] === 'false' ? 'padding-left: 0; padding-right: 0;' : '';
      $top_padding = $shortcode_atts['top_padding'] === 'true' ? '' : 'padding-top: 0;';
      $padding = (bool) $shortcode_atts['side_padding'] || 
                 (bool) $shortcode_atts['top_padding'] ? 
                    sprintf('style="%s %s"', $side_padding, $top_padding ) : '';

      $markup = sprintf( '%s%s%s', 
                         '<div class="elit-accordion__item"' . $padding . '>',
                         $content,
                         '</div>' );

      return do_shortcode( $markup );
    }
  }
  add_shortcode( 'accordion-item', 'elit_accordion_item_shortcode' );
}

/**
 * Change a hyphen to an underscore in the keys to an array.
 *
 * @param array $atts The shortcode attributes
 * @return array $atts The shortcode attributes with replaced keys
 */
function elit_accordion_format_atts( $atts ) {
  
  return array_combine(
    array_map( function( $key ) use ( $atts ) { 
      return str_replace( '-', '_', $key );
    }, array_keys( $atts ) ), 
    array_values( $atts )
  );
}
add_action( 'init' , 'elit_accordion_shortcode_init' );
