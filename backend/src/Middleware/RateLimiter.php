<?php

namespace App\Middleware;

class RateLimiter
{
  public function check()
  {
    $ip = $this->getClientIp();
    $key = md5($ip); // IPアドレスを ファイル名として使いやすい形式(ハッシュ値) に変換
    $limit = env('RATE_LIMIT_LIMIT');
    $period = env('RATE_LIMIT_PERIOD');

    /* 
    // Redis 実装の例
    try {
        $count = $this->redis->get($key);
        if ($count >= $limit) {
          return false;
        }
        $this->redis->set($key, $count + 1, $period);
    
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);

        $count = $redis->get($key) ?: 0;

        if ($count >= $limit) {
            return false;
        }

        // 原子的なインクリメント（マルチスレッド対策）
        $newCount = $redis->incr($key);
        if ($newCount === 1) {
            $redis->expire($key, $period);
        }
        return true;
    } catch (\Exception $e) {
        // Redisが落ちている場合に備えてログを出すか、
        // あるいは制限なしで通す（Fail-safe）などの判断が必要
        error_log("Redis error: " . $e->getMessage());
        return true; 
    }
    */

    $data = $this->loadData($key);
    $now = time();

    $data = $this->ensureValidData($data, $now, $period);

    if ($this->isLimitExceeded($data, $limit)) {
      return false;
    }

    $this->updateAndSave($key, $data);
    return true;
  }

  /**
   * データを読み込む
   * @param string $key
   * @return array|null
   */
  private function loadData(string $key): array | null {
    $file = $this->getStoragePath($key);

    return file_exists($file) ? json_decode(file_get_contents($file), true) : null;
  }

  /**
   * データを検証する
   * @param array $data
   * @param int $now
   * @param int $period
   * @return array
   */
  private function ensureValidData(array $data, int $now, int $period): array | null {
    if ($data && $now > $data['expires_at']) {
      return ['count' => 0, 'expires_at' => $now + $period];
    }

    return null;
  }

  /**
   * データを保存する
   * @param string $key
   * @param array $data
   */
  private function saveData(string $key, array $data) {
    $file = $this->getStoragePath($key);
    file_put_contents($file, json_encode($data));
  }

  /**
   * クライアントのIPアドレスを取得する
   * @return string
   */
  private function getClientIp() {
    return $_SERVER['REMOTE_ADDR'];
  }

  /**
   * データを保存するパスを取得する
   * @param string $key
   * @return string
   */
  private function getStoragePath(string $key): string {
    return "/tmp/rate_limit/" . $key . ".json";
  }

  /**
   * 制限を超えているかどうかを判定する
   * @param array $data
   * @param int $limit
   * @return bool
   */
  private function isLimitExceeded(array $data, int $limit): bool {
    return $data['count'] >= $limit;
  }

  /**
   * データを更新して保存する
   * @param string $key
   * @param array $data
   */
  private function updateAndSave(string $key, array $data) {
    $data['count']++;
    $this->saveData($key, $data);
  }
}
