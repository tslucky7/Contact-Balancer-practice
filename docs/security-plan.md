# セキュリティ対策 実装計画

## Context

お問い合わせフォーム（PHP + TypeScript）に最低限必要なセキュリティ対策を導入する。
既にバリデーション（JSON Schema共有）、SQLインジェクション対策（PDO prepared statements）、環境変数管理（phpdotenv）は実装済み。
残る主要なギャップに対処する。

---

## Step 1: テスト用エンドポイントの削除

DB接続情報や内部エラーを漏洩するテストファイルを削除する。

**削除対象:**
- `backend/public/db_env_test.php`
- `backend/public/api/db_test.php`
- `backend/public/api/http_test.php`

---

## Step 2: エラーメッセージの漏洩修正

[InquiryController.php:75](backend/src/Controllers/InquiryController.php#L75) で `$e->getMessage()` がクライアントに返されており、Backlog/Slack APIのエラー詳細が漏洩する。

**変更箇所:** `backend/src/Controllers/InquiryController.php` (L70-78)

- `$e->getMessage()` → ログに記録し、クライアントには汎用メッセージを返す
- `$logger` を `InquiryController` のコンストラクタに追加して Monolog で記録する

---

## Step 3: セキュリティヘッダー + CORS

**新規作成:** `backend/src/Middleware/SecurityHeaders.php`

設定するヘッダー:
- `Access-Control-Allow-Origin` (環境変数 `CORS_ALLOWED_ORIGIN` から取得)
- `Access-Control-Allow-Methods: POST, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type`
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Content-Security-Policy: default-src 'none'; frame-ancestors 'none'` (JSONレスポンス用)
- `Strict-Transport-Security` (HTTPS時のみ)
- OPTIONS プリフライトの処理 (204を返して終了)

**変更箇所:** [inquiries.php](backend/public/api/inquiries.php) の先頭で呼び出す

---

## Step 4: CSRF対策（Origin検証）

セッション不要のJSON APIのため、トークン方式ではなく Origin/Referer ヘッダー検証を採用。

**新規作成:** `backend/src/Middleware/CsrfGuard.php`

- `Origin` ヘッダーまたは `Referer` ヘッダーから送信元を抽出
- `CORS_ALLOWED_ORIGIN` と一致しなければ 403 で拒否
- POSTリクエストのみ検証

**変更箇所:** [inquiries.php](backend/public/api/inquiries.php) で SecurityHeaders の後に呼び出す

---

## Step 5: レート制限

Redis不要のファイルベース実装。IPアドレスごとに制限。

**新規作成:** `backend/src/Middleware/RateLimiter.php`

- `/tmp/rate_limit/` に IP ハッシュごとの JSON ファイル
- デフォルト: 1分あたり5リクエスト
- 超過時は 429 + `Retry-After` ヘッダー

**変更箇所:** [inquiries.php](backend/public/api/inquiries.php) で CSRF チェックの後に呼び出す

---

## Step 6: フロントエンド・設定の整備

**6a.** 本番ビルドで console.log を除去

[vite.config.ts](frontend/vite.config.ts) に追加:
```typescript
esbuild: {
  drop: ['console', 'debugger'],
},
```

**6b.** `.env.example` を作成（認証情報のプレースホルダのみ）

---

## inquiries.php の最終的な実行順序

```
SecurityHeaders::apply()  ← CORS + セキュリティヘッダー (OPTIONS は即終了)
CsrfGuard::verify()      ← Origin 検証 (POST のみ)
RateLimiter::check()      ← レート制限
InquiryController         ← アプリケーションロジック (既存)
```

---

## スコープ外（意図的に除外）

- Backlog API キーのヘッダー移行 → ベンダー公式のクエリパラメータ方式であり、サーバー間通信のみ
- フロントエンドHTMLのCSP → Vite/静的ホスティングが提供するため PHP では不要
- Redis ベースのレート制限 → 単一エンドポイントにはオーバースペック
- XSS対策 → 調査の結果 `textContent` を使用しており `innerHTML` は未使用。対策済み

---

## 検証方法

1. テストエンドポイント削除後、各URLにアクセスして404を確認
2. 意図的にバリデーションエラー/サーバーエラーを発生させ、内部情報が漏れないことを確認
3. `curl -I` でレスポンスヘッダーにセキュリティヘッダーが含まれることを確認
4. 異なるOriginからのPOSTが403で拒否されることを確認
5. 短時間に6回以上リクエストを送信して429が返ることを確認
6. `npm run build` で本番ビルドに console.log が含まれないことを確認
