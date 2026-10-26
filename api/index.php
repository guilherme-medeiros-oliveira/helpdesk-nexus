<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

if ($path == '' || $path == 'index.php') {
    include __DIR__ . '/../index.php';
} else if (file_exists(__DIR__ . '/../' . $path)) {
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if ($ext == 'php') {
        include __DIR__ . '/../' . $path;
    } else {
        $file = __DIR__ . '/../' . $path;
        $mime = mime_content_type($file);
        header("Content-Type: $mime");
        readfile($file);
    }
} else {
    include __DIR__ . '/../index.php';
}
?>
