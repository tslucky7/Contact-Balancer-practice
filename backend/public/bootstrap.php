<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->safeLoad();

function env(string $key, ?string $default = null): ?string {
    $v = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($v === false || $v === null || $v === '') {
        return $default;
    } else {
        return  (string)$v;
    }
}

// 3) logディレクトリの初期化
$logDir = __DIR__ . '/../storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}

// 日付ごとにlogファイルを作成
$logger = new Logger('app');
$logFile = $logDir . '/app.log';
$logger->pushHandler(new RotatingFileHandler($logFile, 30, Logger::DEBUG));

// このファイルを require した側で $logger を使えるように返す
return ['logger' => $logger];
