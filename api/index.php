<?php
chdir(__DIR__ . '/..');
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// se pedir um arquivo php que existe (registrar.php, dashboard.php) -> abre ele
if ($uri !== '' && $uri !== 'index.php') {
    $target = __DIR__ . '/../' . $uri;
    if (is_file($target) && substr($target, -4) === '.php') {
        require $target;
        exit;
    }
}

// se não, abre o login
require __DIR__ . '/../index.php';