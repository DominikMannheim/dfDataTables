<?php
/**
 * Admin-Seiten und Tabellenverwaltung für dfDataTables
 *
 * @package dfDataTables
 * @since 1.0.0
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Registriert die Admin-Menüeinträge
 */
function dfdatatables_register_admin_menu() {
    // Hauptmenü
    add_menu_page(
        __('dfDataTables', 'dfdatatables'),
        __('dfDataTables', 'dfdatatables'),
        'manage_dfdatatables',
        'dfdatatables',
        'dfdatatables_admin_page',
        'dashicons-table',
        80
    );

    // Untermenüs
    add_submenu_page(
        'dfdatatables',
        __('Einstellungen', 'dfdatatables'),
        __('Einstellungen', 'dfdatatables'),
        'manage_dfdatatables',
        'dfdatatables-settings',
        'dfdatatables_settings_page'
    );
}
add_action('admin_menu', 'dfdatatables_register_admin_menu');

/**
 * Rendert die Hauptadmin-Seite
 */
function dfdatatables_admin_page() {
    // Sicherheitsüberprüfung
    if (!current_user_can('manage_dfdatatables')) {
        wp_die(__('Sie haben keine ausreichenden Berechtigungen.', 'dfdatatables'));
    }

    // Handle Actions
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
    $table_id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : '';

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">' . esc_html__('dfDataTables Verwaltung', 'dfdatatables') . '</h1>';
    
    if ($action === 'list') {
        echo '<a href="' . esc_url(admin_url('admin.php?page=dfdatatables&action=new')) . '" class="page-title-action">';
        echo esc_html__('Neue Tabelle', 'dfdatatables');
        echo '</a>';
    }
    
    echo '<hr class="wp-header-end">';

    // Tabs
    $tabs = array(
        'tables' => __('Tabellen', 'dfdatatables'),
        'import' => __('Import', 'dfdatatables'),
        'docs' => __('Dokumentation', 'dfdatatables')
    );
    
    echo '<nav class="nav-tab-wrapper wp-clearfix">';
    foreach ($tabs as $tab => $name) {
        $active = ($tab === 'tables' && $action === 'list') ? ' nav-tab-active' : '';
        echo '<a href="' . esc_url(admin_url('admin.php?page=dfdatatables&tab=' . $tab)) . '" class="nav-tab' . $active . '">';
        echo esc_html($name);
        echo '</a>';
    }
    echo '</nav>';

    // Content basierend auf Action
    switch ($action) {
        case 'new':
            dfdatatables_edit_table_form();
            break;
        case 'edit':
            dfdatatables_edit_table_form($table_id);
            break;
        case 'delete':
            dfdatatables_delete_table($table_id);
            break;
        default:
            dfdatatables_list_tables();
    }

    echo '</div>';
}

/**
 * Zeigt die Liste aller Tabellen an
 */
function dfdatatables_list_tables() {
    $tables = get_option('dfdatatables_tables', []);

    if (empty($tables)) {
        echo '<div class="notice notice-info"><p>' . 
             esc_html__('Keine Tabellen vorhanden. Erstellen Sie Ihre erste Tabelle!', 'dfdatatables') . 
             '</p></div>';
        return;
    }

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th scope="col">' . esc_html__('Name', 'dfdatatables') . '</th>';
    echo '<th scope="col">' . esc_html__('Shortcode', 'dfdatatables') . '</th>';
    echo '<th scope="col">' . esc_html__('Quelle', 'dfdatatables') . '</th>';
    echo '<th scope="col">' . esc_html__('Aktionen', 'dfdatatables') . '</th>';
    echo '</tr></thead><tbody>';

    foreach ($tables as $id => $table) {
        $source_type = isset($table['source_type']) ? $table['source_type'] : 'csv';
        $source_icon = array(
            'csv' => '📄',
            'sql' => '🗃️',
            'api' => '🔌'
        )[$source_type] ?? '❓';

        echo '<tr>';
        echo '<td><strong>' . esc_html($table['name']) . '</strong></td>';
        echo '<td><code>[dfdatatable id="' . esc_attr($id) . '"]</code>';
        echo '<button class="button button-small copy-shortcode" data-shortcode="[dfdatatable id=\"' . esc_attr($id) . '\"]">';
        echo '<span class="dashicons dashicons-clipboard"></span></button></td>';
        echo '<td>' . $source_icon . ' ' . esc_html(ucfirst($source_type)) . '</td>';
        echo '<td class="row-actions">';
        echo '<span class="edit"><a href="' . esc_url(admin_url('admin.php?page=dfdatatables&action=edit&id=' . $id)) . '">' . 
             esc_html__('Bearbeiten', 'dfdatatables') . '</a> | </span>';
        echo '<span class="delete"><a href="' . esc_url(admin_url('admin.php?page=dfdatatables&action=delete&id=' . $id)) . '" '
           . 'class="submitdelete" '
           . 'onclick="return confirm(\'' . esc_js(__('Möchten Sie diese Tabelle wirklich löschen?', 'dfdatatables')) . '\');">' 
           . esc_html__('Löschen', 'dfdatatables') . '</a></span>';
        echo '</td></tr>';
    }
    
    echo '</tbody></table>';

    // JavaScript für Shortcode-Kopieren
    echo '<script>
    document.querySelectorAll(".copy-shortcode").forEach(button => {
        button.addEventListener("click", function() {
            const shortcode = this.dataset.shortcode;
            navigator.clipboard.writeText(shortcode).then(() => {
                this.innerHTML = "<span class=\"dashicons dashicons-yes\"></span>";
                setTimeout(() => {
                    this.innerHTML = "<span class=\"dashicons dashicons-clipboard\"></span>";
                }, 1000);
            });
        });
    });
    </script>';
}
?>
