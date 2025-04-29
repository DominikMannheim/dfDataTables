<?php
// API-Integration (später für FHIR, JSON APIs etc.)

function dfdatatables_fetch_api_data($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return ['error' => $response->get_error_message()];
    }
    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}
?>
