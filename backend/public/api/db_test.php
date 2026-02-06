<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require dirname(__DIR__) . '/bootstrap.php';

/**
 * 1) 接続情報（まずは直書きでOK。動いたら環境変数に逃がす）
 */
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

/**
 * 2) DSN（Data Source Name）を組み立てる
 *    - mysql:host=...;port=...;dbname=...;charset=...
 */
$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

/**
 * 3) PDOを作る（=接続）
 *    - 例外を投げる設定にして、エラーを握りつぶさない
 */
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 4) 接続確認：SELECT 1 を叩いて結果を表示
    $stmt = $pdo->query('SELECT 1 AS ok');
    $row = $stmt->fetch();

    // echo "DB CONNECT OK\n";
    // echo "SELECT 1 => " . ($row['ok'] ?? 'null') . "\n";
    echo json_encode([
        'ok' => true,
        'db' => $db,
        'select1' => $row['ok'] ?? null,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo "DB CONNECT FAILED\n";
    echo $e->getMessage() . "\n";
}
