<?php
declare(strict_types=1);

/*
 * AcheiNaPuc — núcleo da aplicação.
 * Carregado por toda página em public/. Configura sessão, cabeçalhos de
 * segurança, banco de dados e funções auxiliares.
 */

const APP_ROOT = __DIR__ . '/..';
const APP_NAME = 'AcheiNaPuc';

date_default_timezone_set('America/Sao_Paulo');

// Carrega um .env local (apenas para desenvolvimento; em produção use variáveis do host).
$envFile = APP_ROOT . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if ($line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if (getenv($k) === false) putenv("$k=" . trim($v, "\"'"));
    }
}

function env(string $key, ?string $default = null): ?string
{
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}

$isDebug = env('APP_DEBUG') === '1';
ini_set('display_errors', $isDebug ? '1' : '0');
error_reporting(E_ALL);

// HTTPS atrás de proxy (Render, Koyeb etc.)
$isHttps = ($_SERVER['HTTPS'] ?? '') === 'on'
    || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

if (PHP_SAPI === 'cli') {
    require __DIR__ . '/db.php';
    require __DIR__ . '/helpers.php';
    return;
}

session_name('acheinapuc');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; script-src 'self'; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");

require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';
