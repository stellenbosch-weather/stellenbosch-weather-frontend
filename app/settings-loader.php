<?php
function loadSettings() {
    $path = dirname(__DIR__) . '/settings.conf';
    if (!is_file($path)) {
        throw new RuntimeException('No settings.conf found at ' . $path);
    }
    $settings = parse_ini_file($path, true);
    if ($settings === false) {
        throw new RuntimeException('Unable to parse settings file: ' . $path);
    }
    return $settings;
}
