<?php
declare(strict_types=1);

namespace App\Services;

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
   * @param string $subject
   * @param string $requestId
   * @param string $issueKey
   * @return void
   */
  public function sendSlackMessage(string $subject, string $requestId, string $issueKey): void {
    [$slackCode, $slackBody, $slackErr] = $this->httpClient->curlJsonPost(env('SLACK_WEBHOOK_URL'), [
      'text' => "新規問い合わせ: {$subject}\n受付ID: {$requestId}\nBacklog: {$issueKey}",
    ]);

    if ($slackCode < 200 || $slackCode >= 300) {
      throw new RuntimeException("Slack webhook error: HTTP {$slackCode} {$slackErr} body={$slackBody}");
    }
  }
}
