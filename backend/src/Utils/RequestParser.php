<?php
declare(strict_types=1);

namespace App\Utils;

class RequestParser {
  /**
   * リクエストボディをJSON形式で取得
   * 
   * - file_get_contents: ファイルの内容を取得
   * - php://input: リクエストボディを取得
   * - json_decode: JSONをデコードし、phpの値にする。第二がtrueの場合は配列として返す
   * @return array
   */
  public static function getJsonInput(): array {
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
  }
}
