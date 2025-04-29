# dfDataTables WordPress Plugin

Ein leistungsstarkes und flexibles WordPress-Plugin zur Verwaltung und Anzeige von Tabellen mit verschiedenen Datenquellen.

## Features

- 📊 Flexible Tabellengestaltung
- 📁 CSV-Import
- 🗃️ SQL-Datenbankanbindung
- 🔌 API-Integration
- 🖱️ Doppelklick-Aktionen
- 🎨 Anpassbares Design
- 🔒 Sicherheitsoptimiert

## Installation

1. Laden Sie das Plugin in das `/wp-content/plugins/` Verzeichnis hoch
2. Aktivieren Sie das Plugin im WordPress Admin-Bereich
3. Gehen Sie zu "dfDataTables" im Admin-Menü

## Verwendung

### Shortcode

Verwenden Sie den Shortcode `[dfdatatable id="IHRE_TABELLEN_ID"]` um eine Tabelle anzuzeigen.

### Datenquellen

#### CSV-Import
- Unterstützt CSV-Dateien mit beliebigen Trennzeichen
- Automatische Spaltenerkennung
- UTF-8 Encoding

#### SQL-Verbindung
- Sichere Prepared Statements
- Unterstützung für komplexe Abfragen
- Automatische Aktualisierung

#### API-Integration
- REST API Unterstützung
- JSON-Datenverarbeitung
- Caching-Optionen

### Doppelklick-Aktionen

Konfigurieren Sie benutzerdefinierte Aktionen für Doppelklicks auf Tabellenzeilen:
- URL-Templates mit Platzhaltern
- Dynamische Parametergenerierung
- Sicherheitsüberprüfungen

## Sicherheit

- WordPress Capability System
- Nonce-Überprüfung
- Prepared Statements
- Escape/Sanitize aller Ausgaben
- ABSPATH Checks

## Entwickler-Dokumentation

### Hooks

#### Filter
```php
// Modifiziere Tabellendaten vor der Anzeige
apply_filters('dfdatatables_table_data', $data, $table_id);

// Passe Tabellenoptionen an
apply_filters('dfdatatables_table_options', $options, $table_id);
```

#### Actions
```php
// Wird ausgeführt vor dem Rendern einer Tabelle
do_action('dfdatatables_before_table', $table_id);

// Wird ausgeführt nach dem Rendern einer Tabelle
do_action('dfdatatables_after_table', $table_id);
```

### Beispiele

#### Eigene Datenquelle hinzufügen
```php
add_filter('dfdatatables_data_sources', function($sources) {
    $sources['custom'] = array(
        'name' => 'Custom Source',
        'handler' => 'my_custom_data_handler'
    );
    return $sources;
});
```

## Changelog

### 1.0.0
- Initiale Version
- Grundlegende Tabellenfunktionen
- CSV, SQL und API Support
- Admin-Interface
- Shortcode-System

## Support

Bei Fragen oder Problemen:
- Erstellen Sie ein Issue auf GitHub
- Kontaktieren Sie uns über die Website

## Lizenz

Dieses Plugin ist unter der GPL v2 oder späteren Version lizenziert.
