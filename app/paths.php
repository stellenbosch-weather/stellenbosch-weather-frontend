<?php
function application_root() {
    return dirname(__DIR__);
}
function runtime_path($directory) {
    return application_root() . '/var/' . $directory;
}
