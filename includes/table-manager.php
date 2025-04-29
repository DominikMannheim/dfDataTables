<?php
// Tabellenmodelle und Speicherung

function dfdatatables_get_table($id) {
    $tables = get_option('dfdatatables_tables', []);
    return isset($tables[$id]) ? $tables[$id] : null;
}

function dfdatatables_save_table($id, $data) {
    $tables = get_option('dfdatatables_tables', []);
    $tables[$id] = $data;
    update_option('dfdatatables_tables', $tables);
}

function dfdatatables_delete_table($id) {
    $tables = get_option('dfdatatables_tables', []);
    if (isset($tables[$id])) {
        unset($tables[$id]);
        update_option('dfdatatables_tables', $tables);
    }
}
?>
