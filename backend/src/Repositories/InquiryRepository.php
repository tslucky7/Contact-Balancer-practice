<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use App\Models\Inquiry;

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
   * @param Inquiry $inquiry
   * @return void
   */
  public function create(Inquiry $inquiry): void {
    $this->pdo->prepare('INSERT INTO inquiries (request_id, name, email, subject, message) VALUES (?,?,?,?,?)')
        ->execute([
          $inquiry->requestId,
          $inquiry->name,
          $inquiry->email,
          $inquiry->subject,
          $inquiry->message,
        ]);
  }

  /**
   * 問い合わせを成功として更新
   * @param Inquiry $inquiry
   * @return void
   */
  public function markSuccess(Inquiry $inquiry): void {
    $this->pdo->prepare('UPDATE inquiries SET status="backlog_created", backlog_issue_id=?, backlog_issue_key=? WHERE request_id=?')
        ->execute([
          $inquiry->backlogIssueId,
          $inquiry->backlogIssueKey,
          $inquiry->requestId,
        ]);
  }

  /**
   * 問い合わせを失敗として更新
   * @param Inquiry $inquiry
   * @return void
   */
  public function markFailed(Inquiry $inquiry): void {
    $this->pdo->prepare('UPDATE inquiries SET status="failed", error_message=? WHERE request_id=?')
        ->execute([
          $inquiry->errorMessage,
          $inquiry->requestId,
        ]);
  }
}
