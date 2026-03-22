<?php

namespace App\Middleware;

class SecurityHeader
{
  /**
   * セキュリティヘッダーとCORS設定を送信する
   */
  public function sendHeaders()
  {
    // 1. CORS設定
    $allowedOrigin = env('CORS_ALLOWED_ORIGIN') ?: '*';
    header("Access-Control-Allow-Origin: " . $allowedOrigin);
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

    // 2. セキュリティヘッダー
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY"); // 古いブラウザのための設定
    header("X-XSS-Protection: 1; mode=block"); // 古いブラウザのための設定
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Content-Security-Policy: default-src 'self';");

    // 3. OPTIONSリクエスト（プリフライト）への対応
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      http_response_code(204); // No Content
      exit;
    }
  }
}
