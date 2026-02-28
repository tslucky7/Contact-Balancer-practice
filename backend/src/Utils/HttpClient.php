<?php
declare(strict_types=1);

namespace App\Utils;

use RuntimeException;

class HttpClient {
  private $curl;

  public function __construct() {
    $this->curl = curl_init() ?: throw new RuntimeException('curl_init failed');
  }

  public function __destruct() {
    unset($this->curl);
  }

  /**
   * JSON形式のリクエストを送信
   * 
   * - curl_init: 他のサーバーに対してhttp通信をするための初期化処理。入れたurlに対して通信を行う。
   * - curl_setopt_array: 複数のオプションを一度に設定する。
   * - curl_exec: 通信を実行
   * - curl_getinfo: 通信の情報を取得
   * - curl_error: 通信のエラーを取得
   * - unset: 通信を終了し、メモリを解放する
   * @param string $url
   * @param array $payload
   * @return array
   */
  public function curlJsonPost(string $url, array $payload): array {
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
    unset($ch);

    return [$code, $body, $err];
  }

  /**
   * フォーム形式のリクエストを送信
   * 
   * - curl_init: 他のサーバーに対してhttp通信をするための初期化処理。入れたurlに対して通信を行う。
   * - curl_setopt_array: 複数のオプションを一度に設定する。
   * - curl_exec: 通信を実行
   * - curl_getinfo: 通信の情報を取得
   * - curl_error: 通信のエラーを取得
   * - unset: 通信を終了し、メモリを解放する
   * @param string $url
   * @param array $fields
   * @return array
   */
  public function curlFormPost(string $url, array $fields): array {
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
    unset($ch);

    return [$code, $body, $err];
  }
}



