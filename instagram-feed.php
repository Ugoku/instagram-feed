<?php 
/*
Plugin Name: Instagram Feed
Plugin URI: https://smashballoon.com/instagram-feed
Description: Display beautifully clean, customizable, and responsive Instagram feeds
Version: 1.5
Author: Smash Balloon, Bureau IMAGO
Author URI: https://smashballoon.com/
License: GPLv2 or later
Text Domain: instagram-feed

Copyright 2017  Smash Balloon LLC (email : hey@smashballoon.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'SBIVER', '1.5' );

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//Include admin
include dirname( __FILE__ ) .'/instagram-feed-admin.php';

// Add shortcodes
add_shortcode('instagram-feed', 'display_instagram');
function display_instagram($atts, $content = null) {

    /******************* SHORTCODE OPTIONS ********************/

    $options = get_option('sb_instagram_settings');
    
    //Pass in shortcode attrbutes
    $atts = shortcode_atts([
        'id' => $options[ 'sb_instagram_user_id' ] ?? '',
        'width' => $options[ 'sb_instagram_width' ] ?? '',
        'widthunit' => $options[ 'sb_instagram_width_unit' ] ?? '',
        'widthresp' => $options[ 'sb_instagram_feed_width_resp' ] ?? '',
        'height' => $options[ 'sb_instagram_height' ] ?? '',
        'heightunit' => $options[ 'sb_instagram_height_unit' ] ?? '',
        'sortby' => $options[ 'sb_instagram_sort' ] ?? '',
        'num' => $options[ 'sb_instagram_num' ] ?? '',
        'imagepadding' => $options[ 'sb_instagram_image_padding' ] ?? '',
        'imagepaddingunit' => $options[ 'sb_instagram_image_padding_unit' ] ?? '',
        'showbutton' => false,
        'showheader' => $options[ 'sb_instagram_show_header' ] ?? '',
        'showbio' => $options[ 'sb_instagram_show_bio' ] ?? '',
        'class' => '',
        'ajaxtheme' => $options[ 'sb_instagram_ajax_theme' ] ?? ''
    ], $atts);


    /******************* VARS ********************/

    //User ID
    $sb_instagram_user_id = trim($atts['id']);

	if ( empty( $sb_instagram_user_id ) ) {
		$sb_instagram_settings = get_option( 'sb_instagram_settings' );
		$at_arr = isset( $sb_instagram_settings[ 'sb_instagram_at' ] ) ? explode( '.', trim( $sb_instagram_settings[ 'sb_instagram_at' ] ), 2) : [];
		$sb_instagram_user_id = $at_arr[0];
	}

    //Container styles
    $sb_instagram_width = $atts['width'];
    $sb_instagram_width_unit = $atts['widthunit'];
    $sb_instagram_height = $atts['height'];
    $sb_instagram_height_unit = $atts['heightunit'];
    $sb_instagram_image_padding = $atts['imagepadding'];
    $sb_instagram_image_padding_unit = $atts['imagepaddingunit'];

    //Set to be 100% width on mobile?
    $sb_instagram_width_resp = $atts[ 'widthresp' ];
    ( $sb_instagram_width_resp == 'on' || $sb_instagram_width_resp == 'true' || $sb_instagram_width_resp == true ) ? $sb_instagram_width_resp = true : $sb_instagram_width_resp = false;
    if( $atts[ 'widthresp' ] == 'false' ) $sb_instagram_width_resp = false;

    //Layout options

    $sb_instagram_styles = 'style="';
    $sb_instagram_styles .= 'max-width: 640px; ';
    if ( !empty($sb_instagram_width) ) $sb_instagram_styles .= 'width:' . $sb_instagram_width . $sb_instagram_width_unit .'; ';
    if ( !empty($sb_instagram_height) && $sb_instagram_height != '0' ) $sb_instagram_styles .= 'height:' . $sb_instagram_height . $sb_instagram_height_unit .'; ';
    if ( !empty($sb_instagram_image_padding) ) $sb_instagram_styles .= 'padding-bottom: ' . (2*intval($sb_instagram_image_padding)).$sb_instagram_image_padding_unit . '; ';
    $sb_instagram_styles .= '"';

    //Header
    $sb_instagram_show_header = $atts['showheader'];
    ( $sb_instagram_show_header == 'on' || $sb_instagram_show_header == 'true' || $sb_instagram_show_header == true ) ? $sb_instagram_show_header = true : $sb_instagram_show_header = false;
    if( $atts[ 'showheader' ] === 'false' ) $sb_instagram_show_header = false;

	$sb_instagram_show_bio = $atts['showbio'];
	( $sb_instagram_show_bio == 'on' || $sb_instagram_show_bio == 'true' || $sb_instagram_show_bio ) ? $sb_instagram_show_bio = 'true' : $sb_instagram_show_bio = 'false';
	if( $atts[ 'showbio' ] === 'false' ) $sb_instagram_show_bio = false;


	//As this is a new option in the update then set it to be true if it doesn't exist yet
	if ( !array_key_exists( 'sb_instagram_show_bio', $options ) ) $sb_instagram_show_bio = 'true';

    //Class
    !empty( $atts['class'] ) ? $sbi_class = ' ' . trim($atts['class']) : $sbi_class = '';

    //Ajax theme
    $sb_instagram_ajax_theme = $atts['ajaxtheme'];
    ( $sb_instagram_ajax_theme == 'on' || $sb_instagram_ajax_theme == 'true' || $sb_instagram_ajax_theme == true ) ? $sb_instagram_ajax_theme = true : $sb_instagram_ajax_theme = false;
    $sb_instagram_ajax_theme = false;


    /******************* CONTENT ********************/

    $sb_instagram_content = '<div id="sb_instagram" class="sbi' . $sbi_class;
    if ( !empty($sb_instagram_height) ) $sb_instagram_content .= ' sbi_fixed_height ';
    if ( $sb_instagram_width_resp ) $sb_instagram_content .= ' sbi_width_resp';
    $sb_instagram_content .=
	    '" '.$sb_instagram_styles .
        ' data-id="' . $sb_instagram_user_id .
	    '" data-num="' . trim($atts['num']) .
	    '" data-res="auto' .
	    '" data-options=\'{&quot;sortby&quot;: &quot;'.$atts['sortby'].'&quot;, &quot;showbio&quot;: &quot;'.$sb_instagram_show_bio.'&quot;, &quot;imagepadding&quot;: &quot;'.$sb_instagram_image_padding.'&quot;}\'>';

    //Header
    if( $sb_instagram_show_header ) $sb_instagram_content .= '<div class="sb_instagram_header" style="padding: '.(2*intval($sb_instagram_image_padding)) . $sb_instagram_image_padding_unit .'; padding-bottom: 0;"></div>';

    //Images container
    $sb_instagram_content .= '<div id="sbi_images" style="padding: '.$sb_instagram_image_padding . $sb_instagram_image_padding_unit .';">';

    //Error messages
    $sb_instagram_error = false;
    if( empty($sb_instagram_user_id) || !isset($sb_instagram_user_id) ){
        $sb_instagram_content .= '<div class="sb_instagram_error"><p>' . __( 'Please enter a User ID on the Instagram Feed plugin Settings page.', 'instagram-feed' ) . '</p></div>';
        $sb_instagram_error = true;
    }
    if( empty($options[ 'sb_instagram_at' ]) || !isset($options[ 'sb_instagram_at' ]) ){
        $sb_instagram_content .= '<div class="sb_instagram_error"><p>' . __( 'Please enter an Access Token on the Instagram Feed plugin Settings page.', 'instagram-feed' ) . '</p></div>';
        $sb_instagram_error = true;
    }

    //Loader
    if( !$sb_instagram_error )
    	$sb_instagram_content .= '<div class="sbi_loader fa-spin"></div>';

    //Load section
    $sb_instagram_content .= '</div><div id="sbi_load"></div>'; //End #sbi_load
    
    $sb_instagram_content .= '</div>'; //End #sb_instagram

    //If using an ajax theme then add the JS to the bottom of the feed
    if ($sb_instagram_ajax_theme){
        $sb_instagram_content .= '<script>var sb_instagram_js_options = { sb_instagram_at: "' . trim($options['sb_instagram_at']) . '" };</script>';
        $sb_instagram_content .= '<script src="' . plugins_url('/js/sb-instagram.js?ver=' . SBIVER , __FILE__) . '"></script>';
    }
 
    //Return our feed HTML to display
    return $sb_instagram_content;
}


#############################

//Allows shortcodes in theme
add_filter('widget_text', 'do_shortcode');

//Enqueue stylesheet
add_action( 'wp_enqueue_scripts', 'sb_instagram_styles_enqueue' );
function sb_instagram_styles_enqueue() {
    wp_register_style( 'sb_instagram_styles', plugins_url('css/sb-instagram.css', __FILE__), [], SBIVER );
    wp_enqueue_style( 'sb_instagram_styles' );

    $options = get_option('sb_instagram_settings');
    if(isset($options['sb_instagram_disable_awesome'])){
        if( !$options['sb_instagram_disable_awesome'] || !isset($options['sb_instagram_disable_awesome']) ) wp_enqueue_style( 'sb-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', [], '4.7.0' );
    }
    
}

//Enqueue scripts
add_action( 'wp_enqueue_scripts', 'sb_instagram_scripts_enqueue' );
function sb_instagram_scripts_enqueue() {
    //Register the script to make it available
    wp_register_script( 'sb_instagram_scripts', plugins_url( '/js/sb-instagram.js' , __FILE__ ), [ 'jquery' ], SBIVER, true ); //http://www.minifier.org/

    //Options to pass to JS file
    $sb_instagram_settings = get_option('sb_instagram_settings');

    //Access token
    isset($sb_instagram_settings[ 'sb_instagram_at' ]) ? $sb_instagram_at = trim($sb_instagram_settings['sb_instagram_at']) : $sb_instagram_at = '';

    $data = [
        'sb_instagram_at' => $sb_instagram_at
    ];

    isset($sb_instagram_settings[ 'sb_instagram_ajax_theme' ]) ? $sb_instagram_ajax_theme = trim($sb_instagram_settings['sb_instagram_ajax_theme']) : $sb_instagram_ajax_theme = '';
    ( $sb_instagram_ajax_theme == 'on' || $sb_instagram_ajax_theme == 'true' || $sb_instagram_ajax_theme == true ) ? $sb_instagram_ajax_theme = true : $sb_instagram_ajax_theme = false;

    //Enqueue it to load it onto the page
    if( !$sb_instagram_ajax_theme ) wp_enqueue_script('sb_instagram_scripts');

    //Pass option to JS file
    wp_localize_script('sb_instagram_scripts', 'sb_instagram_js_options', $data);
}

//Custom CSS
add_action( 'wp_head', 'sb_instagram_custom_css' );
function sb_instagram_custom_css() {
    //Show CSS if an admin (so can see Hide Photos link), or if hiding some photos
	if (!current_user_can('manage_options')) {
		return;
	}

    echo '<!-- Instagram Feed CSS -->';
    echo "\r\n";
    echo '<style type="text/css">';
    echo "\r\n";
    echo "#sbi_mod_error{ display: block; }";
    echo "\r\n";
    echo '</style>';
    echo "\r\n";
}


//Custom JS
add_action( 'wp_footer', 'sb_instagram_custom_js' );
function sb_instagram_custom_js() {
    $options = get_option('sb_instagram_settings');
    isset($options[ 'sb_instagram_custom_js' ]) ? $sb_instagram_custom_js = trim($options['sb_instagram_custom_js']) : $sb_instagram_custom_js = '';

	if (!empty($sb_instagram_custom_js)) {
		echo '<!-- Instagram Feed JS -->' .  "\r\n";
		echo '<script>' .  "\r\n";
		echo 'jQuery( document ).ready(function($) {' . "\r\n";
		echo 'window.sbi_custom_js = function(){' .  "\r\n";
		echo stripslashes($sb_instagram_custom_js) .  "\r\n";
		echo '}' .  "\r\n";
		echo '});' .  "\r\n";
		echo '</script>' .  "\r\n";
	}
}

if ( ! function_exists( 'sb_remove_style_version' ) ) {
	function sb_remove_style_version( $src, $handle ){

		if ( $handle === 'sb-font-awesome' ) {
			$parts = explode( '?ver', $src );
			return $parts[0];
		} else {
			return $src;
		}

	}
	add_filter( 'style_loader_src', 'sb_remove_style_version', 15, 2 );
}

// Load plugin textdomain
add_action( 'init', 'sb_instagram_load_textdomain' );
function sb_instagram_load_textdomain() {
	load_plugin_textdomain('instagram-feed', false, basename( dirname(__FILE__) ) . '/languages');
}

//Run function on plugin activate
function sb_instagram_activate() {
    $options = get_option('sb_instagram_settings');
    $options[ 'sb_instagram_show_header' ] = true;
    update_option( 'sb_instagram_settings', $options );
}
register_activation_hook( __FILE__, 'sb_instagram_activate' );

//Uninstall
function sb_instagram_uninstall()
{
    if ( ! current_user_can( 'activate_plugins' ) )
        return;

    //If the user is preserving the settings then don't delete them
    $options = get_option('sb_instagram_settings');
    $sb_instagram_preserve_settings = $options[ 'sb_instagram_preserve_settings' ];
    if($sb_instagram_preserve_settings) return;

    //Settings
    delete_option( 'sb_instagram_settings' );
}
register_uninstall_hook( __FILE__, 'sb_instagram_uninstall' );
