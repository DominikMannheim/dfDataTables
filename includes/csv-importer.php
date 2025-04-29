<?php
// CSV-Datenverarbeitung

function dfdatatables_parse_csv($filepath) {
    $data = [];
    if (($handle = fopen($filepath, 'r')) !== FALSE) {
        $header = fgetcsv($handle, 1000, ";");
        while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}
?>
