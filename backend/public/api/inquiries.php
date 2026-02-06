<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
  exit;
}

function env(string $key): string {
  $v = getenv($key);
  if ($v === false || $v === '') throw new RuntimeException("Missing env: {$key}");
  return $v;
}

function json_input(): array {
  $raw = file_get_contents('php://input') ?: '';
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function ulid_like(): string {
  // 簡易: 26文字の英数字。練習用途（本気ならULIDライブラリ推奨）
  $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
  $s = '';
  for ($i=0; $i<26; $i++) $s .= $chars[random_int(0, strlen($chars)-1)];
  return $s;
}

function curl_json_post(string $url, array $payload): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
  ]);
  $body = curl_exec($ch);
  $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err  = curl_error($ch);
  curl_close($ch);
  return [$code, $body, $err];
}

function curl_form_post(string $url, array $fields): array {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
    CURLOPT_POSTFIELDS => http_build_query($fields),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
  ]);
  $body = curl_exec($ch);
  $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err  = curl_error($ch);
  curl_close($ch);
  return [$code, $body, $err];
}

$data = json_input();
$name = trim((string)($data['name'] ?? ''));
$email = trim((string)($data['email'] ?? ''));
$subject = trim((string)($data['subject'] ?? ''));
$message = trim((string)($data['message'] ?? ''));

if ($name === '' || $email === '' || $subject === '' || $message === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => '必須項目が不足しています']);
  exit;
}

$requestId = ulid_like();

$pdo = new PDO(
  sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', env('DB_HOST'), env('DB_NAME')),
  env('DB_USER'),
  env('DB_PASS'),
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// 1) まずDB保存（受付番号を確保）
$pdo->prepare('INSERT INTO inquiries (request_id, name, email, subject, message) VALUES (?,?,?,?,?)')
    ->execute([$requestId, $name, $email, $subject, $message]);

try {
  // 2) Backlog課題作成（必須: projectId/summary/issueTypeId/priorityId）
  // Backlogは課題登録APIを提供（API Key/OAuth2）。 :contentReference[oaicite:8]{index=8}
  $spaceKey = env('BACKLOG_SPACE_KEY'); // 例: "yourspace"
  $apiKey = env('BACKLOG_API_KEY');
  $url = "https://{$spaceKey}.backlog.com/api/v2/issues?apiKey=" . rawurlencode($apiKey);

  $description = "受付ID: {$requestId}\n\n【氏名】{$name}\n【メール】{$email}\n\n{$message}";
  [$code, $body, $err] = curl_form_post($url, [
    'projectId'   => env('BACKLOG_PROJECT_ID'),
    'summary'     => "[問い合わせ] {$subject}",
    'description' => $description,
    'issueTypeId' => env('BACKLOG_ISSUE_TYPE_ID'),
    'priorityId'  => env('BACKLOG_PRIORITY_ID'),
  ]);

  if ($code < 200 || $code >= 300) {
    throw new RuntimeException("Backlog API error: HTTP {$code} {$err} body={$body}");
  }

  $issue = json_decode($body, true);
  $issueId = $issue['id'] ?? null;
  $issueKey = $issue['issueKey'] ?? null;

  // 3) Slack通知（Incoming Webhook）
  // Webhookは「JSON payload を POST」するだけ。 :contentReference[oaicite:9]{index=9}
  $slackWebhook = env('SLACK_WEBHOOK_URL');
  [$scode, $sbody, $serr] = curl_json_post($slackWebhook, [
    'text' => "新規問い合わせ: {$subject}\n受付ID: {$requestId}\nBacklog: {$issueKey}",
  ]);
  if ($scode < 200 || $scode >= 300) {
    // 通知失敗は致命にしない設計もあり（今回は例として例外にする）
    throw new RuntimeException("Slack webhook error: HTTP {$scode} {$serr} body={$sbody}");
  }

  // 4) DB更新
  $pdo->prepare('UPDATE inquiries SET status="backlog_created", backlog_issue_id=?, backlog_issue_key=? WHERE request_id=?')
      ->execute([$issueId, $issueKey, $requestId]);

  echo json_encode(['ok' => true, 'requestId' => $requestId, 'backlogIssueKey' => $issueKey], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
  $pdo->prepare('UPDATE inquiries SET status="failed", error_message=? WHERE request_id=?')
      ->execute([$e->getMessage(), $requestId]);

  http_response_code(500);
  echo json_encode(['ok' => false, 'requestId' => $requestId, 'error' => '連携処理に失敗しました'], JSON_UNESCAPED_UNICODE);
}
