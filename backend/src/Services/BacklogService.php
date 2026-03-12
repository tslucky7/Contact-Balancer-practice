<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Inquiry;
use App\Utils\HttpClient;
use RuntimeException;

class BacklogService {
  private HttpClient $httpClient;

  public function __construct(HttpClient $httpClient) {
    $this->httpClient = $httpClient;
  }

  public function createIssue(Inquiry $inquiry): array {
    // 2) Backlog課題作成（必須: projectId/summary/issueTypeId/priorityId）
    // Backlogは課題登録APIを提供（API Key/OAuth2）。 :contentReference[oaicite:8]{index=8}
    $spaceKey = env('BACKLOG_SPACE_KEY');
    $apiKey = env('BACKLOG_API_KEY');
    $url = "https://{$spaceKey}.backlog.com/api/v2/issues?apiKey=" . rawurlencode($apiKey);

    $description = "受付ID: {$inquiry->requestId}\n\n【氏名】{$inquiry->name}\n【メール】{$inquiry->email}\n\n{$inquiry->message}";
    [$code, $body, $err] = $this->httpClient->curlFormPost($url, [
      'projectId'   => env('BACKLOG_PROJECT_ID'),
      'summary'     => "[問い合わせ] {$inquiry->subject}",
      'description' => $description,
      'issueTypeId' => env('BACKLOG_ISSUE_TYPE_ID'),
      'priorityId'  => env('BACKLOG_PRIORITY_ID'),
    ]);

    if ($code < 200 || $code >= 300) {
      throw new RuntimeException("Backlog API error: HTTP {$code} {$err} body={$body}");
    }

    $issue = json_decode($body, true);
    $issueId = $issue['id'] ?? null;
    $issueKey = $issue['issueKey'] ?? null;

    return [
      'issueId' => $issueId,
      'issueKey' => $issueKey,
    ];
  }
}

