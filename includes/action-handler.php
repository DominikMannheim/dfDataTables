<?php
// Direktzugriff verhindern
defined('ABSPATH') or die('No script kiddies please!');

// Doppelklick-Action Handler
function dfdatatables_handle_action() {
    // Nonce Überprüfung
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dfdatatables-nonce')) {
        wp_send_json_error(array('message' => __('Sicherheitsüberprüfung fehlgeschlagen', 'dfdatatables')));
    }

    // Berechtigungsprüfung
    if (!current_user_can('manage_dfdatatables')) {
        wp_send_json_error(array('message' => __('Keine Berechtigung', 'dfdatatables')));
    }

    // Daten validieren
    $action_id = isset($_POST['action_id']) ? sanitize_text_field($_POST['action_id']) : '';
    $row_data = isset($_POST['row_data']) ? $_POST['row_data'] : array();

    if (empty($action_id)) {
        wp_send_json_error(array('message' => __('Keine Action ID angegeben', 'dfdatatables')));
    }

    // Hole Action Template
    $table_id = isset($_POST['table_id']) ? sanitize_text_field($_POST['table_id']) : '';
    $table = dfdatatables_get_table($table_id);
    
    if (!$table || !isset($table['actions'][$action_id])) {
        wp_send_json_error(array('message' => __('Ungültige Action', 'dfdatatables')));
    }

    $template = $table['actions'][$action_id]['template'];
    $result = dfdatatables_generate_action_link($row_data, $template);

    wp_send_json_success(array('url' => $result));
}
add_action('wp_ajax_dfdatatables_handle_action', 'dfdatatables_handle_action');

// Generiere Action Link mit Sicherheitsüberprüfung
function dfdatatables_generate_action_link($row, $template) {
    // Validiere und bereinige Daten
    $sanitized_row = array();
    foreach ($row as $key => $value) {
        $sanitized_row[sanitize_key($key)] = sanitize_text_field($value);
    }

    // Ersetze Platzhalter mit bereinigten Werten
    foreach ($sanitized_row as $key => $value) {
        $template = str_replace('{' . $key . '}', urlencode($value), $template);
    }

    return esc_url($template);
}
?>
