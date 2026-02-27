<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Inquiry;
use App\Utils\HttpClient;
use RuntimeException;

/**
 * Slack通知サービス
 * 
 * - public function **sendMessage**: 新規問い合わせをSlackに通知する
 */
class SlackService {
  /**
   * @var HttpClient
   */
  private HttpClient $httpClient;

  public function __construct() {
    $this->httpClient = new HttpClient();
  }

  /**
   * 新規問い合わせをSlackに通知する
   * 
   * - Webhookは「JSON payload を POST」するだけ。 :contentReference[oaicite:9]{index=9}
   * @param Inquiry $inquiry
   * @return void
   */
  public function sendSlackMessage(Inquiry $inquiry): void {
    [$slackCode, $slackBody, $slackErr] = $this->httpClient->curlJsonPost(env('SLACK_WEBHOOK_URL'), [
      'text' => "新規問い合わせ: {$inquiry->subject}\n
      受付ID: {$inquiry->requestId}\n
      Backlog: {$inquiry->backlogIssueKey}\n
      Name: {$inquiry->name}\n
      Email: {$inquiry->email}\n
      Message: {$inquiry->message}",
    ]);

    if ($slackCode < 200 || $slackCode >= 300) {
      throw new RuntimeException("Slack webhook error: HTTP {$slackCode} {$slackErr} body={$slackBody}");
    }
  }
}
