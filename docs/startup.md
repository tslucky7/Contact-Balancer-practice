# 起動方法

## 前提

- PHP 8.3 がローカルにインストール済み（`/usr/local/bin/php`）
  - Apple Containerとは無関係。Homebrewなどでインストールされたもの
  - 確認: `php --version`
- Node.js / npm がインストール済み
- Docker Desktop が起動済み（DB使用時）

---

## 開発環境の起動

### 1. DB（MySQL）

#### Apple Container（現在の開発環境）

machine "dev" が起動していること（`container machine list` で確認）。

ボリュームを作成してからコンテナを起動する（初回のみ `volume create` が必要）。

```bash
container volume create inquiry_mysql
```

```bash
container run -d \
  --name inquiry-mysql \
  -p 3306:3306 \
  -e MYSQL_ROOT_PASSWORD=rootpass \
  -e MYSQL_DATABASE=inquiry \
  -e MYSQL_USER=app \
  -e MYSQL_PASSWORD=apppass \
  -e TZ=Asia/Tokyo \
  -v inquiry_mysql:/var/lib/mysql \
  mysql:8
```

> ⚠️ `.env` のキー名（`DB_USER`, `DB_PASS` 等）はPHP用。mysql イメージが要求するのは `MYSQL_*` 形式なので、上記のように直接値を指定する。

`-p 3306:3306` でホストの `localhost:3306` に転送されるため `.env` の `DB_HOST=127.0.0.1` はそのままでOK。

**machine 再起動後**は `container start inquiry-mysql` で再開（`restart: unless-stopped` 相当は非対応のため手動）。

#### Docker（旧環境・compose.yml 使用）

```bash
docker compose up -d
```

> ⚠️ Apple Container は `docker compose` / `container compose` に非対応。`compose.yml` はDockerのみ使用可能。

### 2. PHPサーバー（バックエンド）

```bash
php -S localhost:8000 -t backend/public
```

`http://localhost:8000/api/inquiries.php` でAPIにアクセス可能。

### 3. Vite（フロントエンド）

```bash
npm run dev
```

デフォルトで `http://localhost:5173` が立ち上がる。

---

## 停止

```bash
# Docker DB停止
docker compose down
```

PHP / Vite は `Ctrl+C` で停止。
