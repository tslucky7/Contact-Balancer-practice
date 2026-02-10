<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$boot = require __DIR__ . '/../bootstrap.php';
/** @var \Monolog\Logger $logger */
$logger = $boot['logger'];

try {
    $host = env('DB_HOST', '127.0.0.1');
    $port = env('DB_PORT', '3306');
    $name = env('DB_NAME');
    $user = env('DB_USER');
    $pass = env('DB_PASS', '');
    $charset = env('DB_CHARSET', 'utf8mb4');

    if (!$name || !$user) {
        throw new RuntimeException('DB_NAME or DB_USER is empty. Check backend/.env');
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $row = $pdo->query('SELECT 1 AS ok')->fetch();

    $logger->info('db_test ok', ['db' => $name]);

    echo json_encode([
        'status' => 'ok',
        'select1' => $row,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    $logger->error('db_test failed', [
        'message' => $e->getMessage(),
        'type' => get_class($e),
    ]);

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
