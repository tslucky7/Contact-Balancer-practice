<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class InquiryRepository {
  private PDO $pdo;

  public function __construct() {
    $this->pdo = new PDO(
      sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', env('DB_HOST'), env('DB_NAME')),
      env('DB_USER'),
      env('DB_PASS'),
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
  }

  /**
   * 問い合わせを作成
   * @param array $data
   * @return void
   */
  public function createInquiry(array $data): void {
    $this->pdo->prepare('INSERT INTO inquiries (request_id, name, email, subject, message) VALUES (?,?,?,?,?)')
        ->execute([$data['request_id'], $data['name'], $data['email'], $data['subject'], $data['message']]);
  }

  /**
   * 問い合わせを更新
   * @param array $data
   * @return void
   */
  public function successUpdateInquiry(array $data): void {
    // 4) DB更新
    $this->pdo->prepare('UPDATE inquiries SET status="backlog_created", backlog_issue_id=?, backlog_issue_key=? WHERE request_id=?')
        ->execute([$data['backlog_issue_id'], $data['backlog_issue_key'], $data['request_id']]);
  }

  /**
   * 問い合わせを失敗にする
   * @param string $requestId
   * @param string $errorMessage
   * @return void
   */
  public function failedUpdateInquiry(string $requestId, string $errorMessage): void {
    $this->pdo->prepare('UPDATE inquiries SET status="failed", error_message=? WHERE request_id=?')
      ->execute([$errorMessage, $requestId]);
  }
}
