<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

/**
 * .env を読み込む（素のPHP）
 */
$envFile = __DIR__ . '/../../.env';
if (!is_readable($envFile)) {
    http_response_code(500);
    echo ".env not found or not readable\n";
    exit;
}

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;

    [$key, $value] = array_map('trim', explode('=', $line, 2));
    $value = trim($value, "\"'");

    $_ENV[$key] = $value;
    putenv("$key=$value");
}

/**
 * DB接続情報を取得
 */
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

/**
 * 接続テスト
 */
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $stmt = $pdo->query('SELECT 1 AS ok');
    $row  = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "DB CONNECT OK\n";
    echo "SELECT 1 => {$row['ok']}\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "DB CONNECT FAILED\n";
    echo $e->getMessage() . "\n";
}
