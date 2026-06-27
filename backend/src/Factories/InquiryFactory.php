<?php
namespace App\Factories;

use App\Models\Inquiry;
use App\Utils\IdGenerator;

class InquiryFactory {
  /**
   * バリデーション済みのデータから Inquiry オブジェクトを生成する
   */
  public static function create(array $data): Inquiry {
    // 生の配列から値を取り出し、加工やID生成を行う（ここが「組み立て」）
    $requestId = $data['request_id'] ?? IdGenerator::ulid();
    $status = Inquiry::STATUS_PENDING;

    // 整えた値を個別に渡して Model を作る
    return new Inquiry(
      $requestId,
      $data['name'],
      $data['email'],
      $data['subject'],
      $data['message'],
      $status
    );
  }
}
