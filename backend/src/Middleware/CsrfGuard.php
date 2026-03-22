<?php

namespace App\Middleware;

use Psr\Log\LoggerInterface;

class CsrfGuard
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function check()
    {
        // 1. POSTリクエスト以外はスキップ
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // 2. 許可されたOriginを取得
        $allowedOrigin = env('CORS_ALLOWED_ORIGIN');
        
        // 3. Originヘッダーを確認（ブラウザが自動付与する）
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        // 4. Originがない場合はRefererからドメインを抽出
        if (empty($origin) && !empty($_SERVER['HTTP_REFERER'])) {
            // 例: http://localhost:5173/contact -> http://localhost:5173
            $referer = $_SERVER['HTTP_REFERER'];
            $parts = parse_url($referer);
            $origin = ($parts['scheme'] ?? 'http') . '://' . ($parts['host'] ?? '');
            
            if (isset($parts['port'])) {
                $origin .= ':' . $parts['port'];
            }
        }

        // 5. .envで許可されたOriginと一致するか
        // 開発環境でOriginが空の場合（直接叩かれた時など）も拒否する
        if ($origin !== $allowedOrigin) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'CSRF protection: Invalid or missing Origin/Referer'
            ]);
            exit;
        }
    }
}
