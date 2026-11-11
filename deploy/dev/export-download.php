<?php
// CLI-only local export harness; writes ZIP bytes to stdout, no download log.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../../public/api/download/utils.php';
register_shutdown_function(function () {
    fwrite(STDERR, 'Peak memory bytes: ' . memory_get_peak_usage(true) . PHP_EOL);
});
downloadHistory(new DateTime(isset($argv[1]) ? $argv[1] : '2026-08-01T00:00:00+02:00'),
    new DateTime(isset($argv[2]) ? $argv[2] : '2026-10-31T23:59:59+02:00'));
