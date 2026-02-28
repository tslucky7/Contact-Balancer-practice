<?php
declare(strict_types=1);

namespace App\Models;

class Inquiry {
  // ステータスの定数定義（RepositoryではなくModelに持たせる）
  public const STATUS_PENDING = 'pending';

  public const STATUS_COMPLETED = 'backlog_created';

  public const STATUS_FAILED = 'failed';

  /** @var string */
  public string $requestId;

  /** @var string */
  public string $name;

  /** @var string */
  public string $email;

  /** @var string */
  public string $subject;

  /** @var string */
  public string $message;
  
  /** @var string */
  public string $status;
  
  /** @var string|null */
  public ?string $backlogIssueId = null;
  
  /** @var string|null */
  public ?string $backlogIssueKey = null;
  
  /** @var string|null */
  public ?string $errorMessage = null;

  public function __construct(array $data) {
    $this->requestId = $data['request_id'] ?? bin2hex(random_bytes(16));
    $this->name = $data['name'];
    $this->email = $data['email'];
    $this->subject = $data['subject'];
    $this->message = $data['message'];
    $this->status = $data['status'] ?? self::STATUS_PENDING;
  }

  /**
   * 成功時の状態変化をモデル内で定義
   */
  public function complete(string $issueId, string $issueKey): void {
    $this->status = self::STATUS_COMPLETED;
    $this->backlogIssueId = $issueId;
    $this->backlogIssueKey = $issueKey;
  }

  /**
   * 失敗時の状態変化
   */
  public function fail(string $message): void {
    $this->status = self::STATUS_FAILED;
    $this->errorMessage = $message;
  }
}
