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
  public ?int $backlogIssueId = null;
  
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
   * @return string
   */
  public function getRequestId(): string {
    return $this->requestId;
  }

  /**
   * @param string $requestId
   * @return void
   */
  public function setRequestId(string $requestId): void {
    $this->requestId = $requestId;
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @param string $name
   * @return void
   */
  public function setName(string $name): void {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getEmail(): string {
    return $this->email;
  }

  /**
   * @param string $email
   * @return void
   */
  public function setEmail(string $email): void {
    $this->email = $email;
  }

  /**
   * @return string
   */
  public function getSubject(): string {
    return $this->subject;
  }

  /**
   * @param string $subject
   * @return void
   */
  public function setSubject(string $subject): void {
    $this->subject = $subject;
  }

  /**
   * @return string
   */
  public function getMessage(): string {
    return $this->message;
  }

  /**
   * @param string $message
   * @return void
   */
  public function setMessage(string $message): void {
    $this->message = $message;
  }

  /**
   * @return string
   */
  public function getStatus(): string {
    return $this->status;
  }

  /**
   * @param string $status
   * @return void
   */
  public function setStatus(string $status): void {
    $this->status = $status;
  }

  /**
   * @return int|null
   */
  public function getBacklogIssueId(): ?int {
    return $this->backlogIssueId;
  }

  /**
   * @param int $backlogIssueId
   * @return void
   */
  public function setBacklogIssueId(int $backlogIssueId): void {
    $this->backlogIssueId = $backlogIssueId;
  }

  /**
   * @return string|null
   */
  public function getBacklogIssueKey(): ?string {
    return $this->backlogIssueKey;
  }

  /**
   * @param string $backlogIssueKey
   * @return void
   */
  public function setBacklogIssueKey(string $backlogIssueKey): void {
    $this->backlogIssueKey = $backlogIssueKey;
  }

  /**
   * @return string|null
   */
  public function getErrorMessage(): ?string {
    return $this->errorMessage;
  }

  /**
   * @param string $errorMessage
   * @return void
   */
  public function setErrorMessage(string $errorMessage): void {
    $this->errorMessage = $errorMessage;
  }

  /**
   * 成功時の状態変化をモデル内で定義
   */
  public function complete(int $issueId, string $issueKey): void {
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
