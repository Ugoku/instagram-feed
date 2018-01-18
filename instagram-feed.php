<?php
/*
Plugin Name: Instagram Feed
Plugin URI: https://smashballoon.com/instagram-feed
Description: Display beautifully clean, customizable, and responsive Instagram feeds
Version: 1.6.0
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

define('SBIVER', '1.7.0');

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
//Include admin
include __DIR__ . '/instagram-feed-admin.php';

// Add shortcodes
add_shortcode('instagram-feed', 'display_instagram');
function display_instagram($atts, $content = null)
{
    /******************* SHORTCODE OPTIONS ********************/

    $options = get_option('sb_instagram_settings');

    //Pass in shortcode attrbutes
    $atts = shortcode_atts([
        'id' => $options['sb_instagram_user_id'] ?? '',
        'sortby' => $options['sb_instagram_sort'] ?? '',
        'num' => $options['sb_instagram_num'] ?? 10,
        'showheader' => $options['sb_instagram_show_header'] ?? '',
        'showbio' => $options['sb_instagram_show_bio'] ?? '',
    ], $atts);

    /******************* VARS ********************/

    //User ID
    $sb_instagram_user_id = trim($atts['id']);

    if (empty($sb_instagram_user_id)) {
        $at_arr = isset($options['sb_instagram_at']) ? explode('.', trim($options['sb_instagram_at']), 2) : [];
        $sb_instagram_user_id = $at_arr[0];
    }

    //Header
    $sb_instagram_show_header = $atts['showheader'];
    $sb_instagram_show_header = ($sb_instagram_show_header == 'on' || $sb_instagram_show_header == 'true' || $sb_instagram_show_header == true);

    $sb_instagram_show_bio = $atts['showbio'];
    $sb_instagram_show_bio = ($sb_instagram_show_bio == 'on' || $sb_instagram_show_bio == 'true' || $sb_instagram_show_bio);

    //As this is a new option in the update then set it to be true if it doesn't exist yet
    if (!array_key_exists('sb_instagram_show_bio', $options)) {
        $sb_instagram_show_bio = 'true';
    }

    /******************* CONTENT ********************/

    $sb_instagram_content = '<div id="sb_instagram"';
    $sb_instagram_content .=
        ' data-id="' . $sb_instagram_user_id . '"' .
        ' data-num="' . trim($atts['num']) . '"' .
        ' data-res="auto"' .
        ' data-options=\'{ &quot;sortby&quot;: &quot;' . $atts['sortby'] . '&quot;, &quot;showbio&quot;: &quot;' . $sb_instagram_show_bio . '&quot; }\'>';

    //Header
    if ($sb_instagram_show_header) {
        $sb_instagram_content .= '<div class="sb_instagram_header" style="padding-bottom: 0;"></div>';
    }

    //Images container
    $sb_instagram_content .= '<div id="sbi_images">';

    //Error messages
    $sb_instagram_error = false;
    if (empty($sb_instagram_user_id) || !isset($sb_instagram_user_id)) {
        $sb_instagram_content .= '<div class="sb_instagram_error"><p>' . __('Please enter a User ID on the Instagram Feed plugin Settings page.', 'instagram-feed') . '</p></div>';
        $sb_instagram_error = true;
    }
    if (empty($options['sb_instagram_at']) || !isset($options['sb_instagram_at'])) {
        $sb_instagram_content .= '<div class="sb_instagram_error"><p>' . __('Please enter an Access Token on the Instagram Feed plugin Settings page.', 'instagram-feed') . '</p></div>';
        $sb_instagram_error = true;
    }

    //Loader
    if (!$sb_instagram_error) {
        $sb_instagram_content .= '<div class="sbi_loader fa-spin"></div>';
    }

    //Load section
    $sb_instagram_content .= '</div><div id="sbi_load"></div>'; //End #sbi_load
    
    $sb_instagram_content .= '</div>'; //End #sb_instagram

    //Return our feed HTML to display
    return $sb_instagram_content;
}


#############################

//Allows shortcodes in theme
add_filter('widget_text', 'do_shortcode');

//Enqueue stylesheet
add_action('wp_enqueue_scripts', 'sb_instagram_styles_enqueue');
function sb_instagram_styles_enqueue()
{
    wp_register_style('sb_instagram_styles', plugins_url('css/sb-instagram.css', __FILE__), [], SBIVER);
    wp_enqueue_style('sb_instagram_styles');
}

//Enqueue scripts
add_action('wp_enqueue_scripts', 'sb_instagram_scripts_enqueue');
function sb_instagram_scripts_enqueue()
{
    //Register the script to make it available
    wp_register_script('instagram-feed', plugins_url('/js/instagramfeed.js', __FILE__), [], '20171123', true);
    wp_register_script('sb_instagram_scripts', plugins_url('/js/sb-instagram.js', __FILE__), ['jquery', 'instagram-feed'], SBIVER, true);

    //Options to pass to JS file
    $sb_instagram_settings = get_option('sb_instagram_settings');

    //Access token
    $sb_instagram_at = isset($sb_instagram_settings['sb_instagram_at']) ? trim($sb_instagram_settings['sb_instagram_at']) : '';

    $data = [
        'sb_instagram_at' => $sb_instagram_at
    ];

    //Enqueue it to load it onto the page
    wp_enqueue_script('sb_instagram_scripts');

    //Pass option to JS file
    wp_localize_script('sb_instagram_scripts', 'sb_instagram_js_options', $data);
}

//Custom CSS
add_action('wp_head', 'sb_instagram_custom_css');
function sb_instagram_custom_css()
{
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



if (!function_exists('sb_remove_style_version')) {
    function sb_remove_style_version($src, $handle)
    {
        if ($handle === 'sb-font-awesome') {
            $parts = explode('?ver', $src);
            return $parts[0];
        } else {
            return $src;
        }
    }
    add_filter('style_loader_src', 'sb_remove_style_version', 15, 2);
}

// Load plugin textdomain
add_action('init', 'sb_instagram_load_textdomain');
function sb_instagram_load_textdomain()
{
    load_plugin_textdomain('instagram-feed', false, basename(__DIR__) . '/languages');
}


//Uninstall
function sb_instagram_uninstall()
{
    if (!current_user_can('activate_plugins')) {
        return;
    }

    // If the user is preserving the settings then don't delete them
    $options = get_option('sb_instagram_settings');
    if ($options['sb_instagram_preserve_settings']) {
        return;
    }

    //Settings
    delete_option('sb_instagram_settings');
}
register_uninstall_hook(__FILE__, 'sb_instagram_uninstall');
