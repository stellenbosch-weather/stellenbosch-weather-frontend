<?php

require_once __DIR__ . '/../../../app/cors.php';

$cacheTtlSeconds = 15 * 60; // 15 minutes
$cacheFile = runtime_path('cache') . '/moon-current-phase-xplanet.png';

if (file_exists($cacheFile)) {
    $mtime = @filemtime($cacheFile);
    if ($mtime !== false && (time() - $mtime) < $cacheTtlSeconds) {
        header('Content-Type: image/png');
        readfile($cacheFile);
        exit;
    }
}

$texturePath = __DIR__ . '/../../../resources/lroc_color_2k.jpg';
if (!file_exists($texturePath)) {
    http_response_code(500);
    echo "Missing texture: " . basename($texturePath);
    exit;
}

$tmpOut = tempnam(runtime_path('tmp'), 'moonxp_');
if ($tmpOut === false) {
    http_response_code(500);
    echo "Failed to create temp file";
    exit;
}
@unlink($tmpOut);
$tmpOutPng = $tmpOut . '.png';

$geometry = '1024x1024';

/**
 * Many xplanet builds expect the body texture ("map") to be set in a config file,
 * not via CLI flags. We'll generate a temp config and pass it via -config.
 *
 * Config syntax: [moon] map=... [[1]](https://xplanet.sourceforge.net/README.config)
 */
$tmpCfg = tempnam(runtime_path('tmp'), 'xplanet_cfg_');
if ($tmpCfg === false) {
    http_response_code(500);
    echo "Failed to create temp config file";
    exit;
}

$cfg =
    "[default]\n" .
    "background=black\n" .
    "\n" .
    "[moon]\n" .
    "map=" . $texturePath . "\n";

if (@file_put_contents($tmpCfg, $cfg) === false) {
    http_response_code(500);
    echo "Failed to write temp config file";
    exit;
}

$cmd =
    escapeshellarg(__DIR__ . '/../../../bin/xplanet') .
    ' -num_times 1' .
    ' -config ' . escapeshellarg($tmpCfg) .
    ' -geometry ' . escapeshellarg($geometry) .
    ' -projection orthographic' .
    ' -body moon' .
    ' -origin earth' .
    ' -output ' . escapeshellarg($tmpOutPng) .
    ' -background black' .
    ' -latitude -34' .
    ' -longitude 19' .
    ' 2>&1';

$out = array();
$exitCode = 0;
@exec($cmd, $out, $exitCode);

// cleanup config ASAP
@unlink($tmpCfg);

if ($exitCode !== 0 || !file_exists($tmpOutPng) || filesize($tmpOutPng) === 0) {
    http_response_code(500);
    echo "xplanet render failed.\n\nTried command:\n" . $cmd . "\n\nExit code:\n" . $exitCode . "\n\nOutput:\n" . implode("\n", $out) . "\n\n";
    exit;
}

$img = @imagecreatefrompng($tmpOutPng);
if ($img !== false) {
    $rotated = imagerotate($img, 180, 0);
    if ($rotated !== false) {
        imagepng($rotated, $tmpOutPng);
        imagedestroy($rotated);
    }
    imagedestroy($img);
}


@rename($tmpOutPng, $cacheFile);
header('Content-Type: image/png');
readfile($cacheFile);
