<?php
// CLI-only query probe: uses the local settings.conf, writes no database rows.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../../app/database-connector.php';
$stream = in_array('--stream', $argv, true);
register_shutdown_function(function () {
    fwrite(STDERR, 'Peak memory bytes: ' . memory_get_peak_usage(true) . PHP_EOL);
});
$db = new DatabaseConnector();
$conn = $db->getConnection();
if ($stream) { $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false); }
$stmt = $conn->prepare('SELECT * FROM SB_TMin WHERE TimeStamp BETWEEN :start AND :end ORDER BY TimeStamp');
$stmt->execute(array(':start' => '2026-08-01 00:00:00', ':end' => '2026-10-31 23:59:59'));
if ($stream) {
    $count = 0;
    while ($stmt->fetch(PDO::FETCH_ASSOC) !== false) { ++$count; }
} else {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($rows);
}
echo 'Rows: ', $count, PHP_EOL;
