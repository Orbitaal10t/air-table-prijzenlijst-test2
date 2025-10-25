<?php
/**
 * Plugin Name: Airtable Prijzenlijst
 * Description: Meerdere Airtable-tabellen als shortcodes met globale navigatie.
 * Version: 2.0
 * Author: Harm van 't Leven
 */

if (!defined('ABSPATH')) exit;

// Config
$config = include plugin_dir_path(__FILE__) . 'airtable-config.php';

// Includes
require_once plugin_dir_path(__FILE__) . 'includes/functions-common.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-prijzenlijst.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-global-nav.php';

// Registratie shortcodes
add_shortcode('airtable_prijzenlijst', ['Prijzenlijst_Shortcode', 'render']);
add_shortcode('prijzenlijst_navigatie', ['Global_Nav_Shortcode', 'render']);

// Enqueue assets
function airtable_prijzenlijst_assets() {
    wp_enqueue_style('prijzenlijst-css', plugin_dir_url(__FILE__) . 'assets/css/prijzenlijst.css', [], '2.0');
    wp_enqueue_script('prijzenlijst-js', plugin_dir_url(__FILE__) . 'assets/js/prijzenlijst.js', ['jquery'], '2.0', true);

    wp_enqueue_style('global-nav-css', plugin_dir_url(__FILE__) . 'assets/css/global-nav.css', [], '2.0');
    wp_enqueue_script('global-nav-js', plugin_dir_url(__FILE__) . 'assets/js/global-nav.js', ['jquery'], '2.0', true);
}
add_action('wp_enqueue_scripts', 'airtable_prijzenlijst_assets');