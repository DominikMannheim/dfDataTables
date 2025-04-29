<?php
/*
Plugin Name: dfDataTables
Plugin URI: https://df-informatik.com
Description: Leichtes, flexibles Tabellen-Plugin für WordPress mit CSV-, SQL- und API-Datenquellen und Doppelklick-Aktionen.
Version: 1.0.0
Author: Dominik Funkhauser
Author URI: https://df-informatik.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dfdatatables
Domain Path: /languages
*/

// Direktzugriff verhindern
defined('ABSPATH') or die('No script kiddies please!');

// Konstanten definieren
define('DFDATATABLES_PATH', plugin_dir_path(__FILE__));
define('DFDATATABLES_URL', plugin_dir_url(__FILE__));
define('DFDATATABLES_VERSION', '1.0.0');

// Dateien einbinden
require_once DFDATATABLES_PATH . 'includes/admin-pages.php';
require_once DFDATATABLES_PATH . 'includes/table-manager.php';
require_once DFDATATABLES_PATH . 'includes/csv-importer.php';
require_once DFDATATABLES_PATH . 'includes/sql-connector.php';
require_once DFDATATABLES_PATH . 'includes/api-connector.php';
require_once DFDATATABLES_PATH . 'includes/action-handler.php';

// Activation Hook
register_activation_hook(__FILE__, 'dfdatatables_activate');
function dfdatatables_activate() {
    // Erstelle Standard-Optionen bei Erstinstallation
    if (!get_option('dfdatatables_tables')) {
        add_option('dfdatatables_tables', array());
    }
    
    // Setze Berechtigungen für Admin
    $role = get_role('administrator');
    $role->add_cap('manage_dfdatatables');
    
    // Flush Rewrite Rules für Custom Post Types (falls benötigt)
    flush_rewrite_rules();
}

// Scripts und Styles
function dfdatatables_enqueue_assets() {
    // Admin Styles und Scripts
    if (is_admin()) {
        wp_enqueue_style(
            'dfdatatables-admin-css',
            DFDATATABLES_URL . 'assets/css/admin.css',
            array(),
            DFDATATABLES_VERSION
        );
    }
    
    // Frontend Styles und Scripts
    wp_enqueue_style(
        'dfdatatables-css',
        DFDATATABLES_URL . 'assets/css/dfdatatables.css',
        array(),
        DFDATATABLES_VERSION
    );
    
    wp_enqueue_script(
        'dfdatatables-js',
        DFDATATABLES_URL . 'assets/js/dfdatatables.js',
        array('jquery'),
        DFDATATABLES_VERSION,
        true
    );
    
    // Localize Script für AJAX
    wp_localize_script('dfdatatables-js', 'dfDatatablesAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dfdatatables-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'dfdatatables_enqueue_assets');

// Shortcode Registrierung
function dfdatatables_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => ''
    ], $atts);

    if (empty($atts['id'])) {
        return '<p><strong>dfDataTables:</strong> Keine Tabellen-ID angegeben.</p>';
    }

    return dfdatatables_render_table($atts['id']);
}
add_shortcode('dfdatatable', 'dfdatatables_shortcode');

// Plugin-Einstellungslink in der Plugin-Liste
function dfdatatables_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=dfdatatables-settings') . '">' . 
                     esc_html__('Einstellungen', 'dfdatatables') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dfdatatables_plugin_action_links');

// Admin Notices für wichtige Meldungen
function dfdatatables_admin_notices() {
    if (!current_user_can('manage_dfdatatables')) {
        return;
    }
    
    $notices = get_option('dfdatatables_admin_notices', array());
    foreach ($notices as $notice_key => $notice) {
        echo '<div class="notice notice-' . esc_attr($notice['type']) . ' is-dismissible"><p>' . 
             esc_html($notice['message']) . '</p></div>';
    }
    
    // Notices nach Anzeige löschen
    update_option('dfdatatables_admin_notices', array());
}
add_action('admin_notices', 'dfdatatables_admin_notices');
?>
