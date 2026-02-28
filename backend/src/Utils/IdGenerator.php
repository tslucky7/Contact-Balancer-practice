<?php
declare(strict_types=1);

namespace App\Utils;

/**
 * ULID風のIDを生成
 */
class IdGenerator {
  /**
   * ULID風のIDを生成
   * @return string
   */
  public static function ulidLike(): string {
    // 簡易: 26文字の英数字。練習用途（本気ならULIDライブラリ推奨）
    $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
    $s = '';
    for ($i=0; $i<26; $i++) $s .= $chars[random_int(0, strlen($chars)-1)];
    return $s;
  }
}
