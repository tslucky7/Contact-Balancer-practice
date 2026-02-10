<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$boot = require __DIR__ . '/../bootstrap.php';
$logger = $boot['logger'];

try {
    $client = new \GuzzleHttp\Client([
        'timeout' => 5.0,
    ]);

    $res = $client->get('https://httpbin.org/get');
    $data = json_decode((string)$res->getBody(), true);

    $logger->info('http_test ok', ['status' => $res->getStatusCode()]);

    echo json_encode([
        'status' => 'ok',
        'statusCode' => $res->getStatusCode(),
        'origin' => $data['origin'] ?? null,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    $logger->error('http_test failed', ['message' => $e->getMessage()]);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
