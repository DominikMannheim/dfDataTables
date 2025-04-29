<?php
// Beim Plugin löschen alles aufräumen
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
delete_option('dfdatatables_tables');
?>
