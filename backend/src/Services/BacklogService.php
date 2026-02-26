<?php
declare(strict_types=1);

namespace App\Services;

class BacklogService {
  public function createIssue(string $requestId, string $name, string $email, string $subject, string $message): array {
    // 2) Backlog課題作成（必須: projectId/summary/issueTypeId/priorityId）
    // Backlogは課題登録APIを提供（API Key/OAuth2）。 :contentReference[oaicite:8]{index=8}
    // $spaceKey = env('BACKLOG_SPACE_KEY'); // 例: "yourspace"
    // $apiKey = env('BACKLOG_API_KEY');
    // $url = "https://{$spaceKey}.backlog.com/api/v2/issues?apiKey=" . rawurlencode($apiKey);

    // $description = "受付ID: {$requestId}\n\n【氏名】{$name}\n【メール】{$email}\n\n{$message}";
    // [$code, $body, $err] = curl_form_post($url, [
    //   'projectId'   => env('BACKLOG_PROJECT_ID'),
    //   'summary'     => "[問い合わせ] {$subject}",
    //   'description' => $description,
    //   'issueTypeId' => env('BACKLOG_ISSUE_TYPE_ID'),
    //   'priorityId'  => env('BACKLOG_PRIORITY_ID'),
    // ]);

    // if ($code < 200 || $code >= 300) {
    //   throw new RuntimeException("Backlog API error: HTTP {$code} {$err} body={$body}");
    // }

    // $issue = json_decode($body, true);
    // $issueId = $issue['id'] ?? null;
    // $issueKey = $issue['issueKey'] ?? null;
    $issueId = 12345;
    $issueKey = "dummyIssueKey";

    return [
      'issueId' => $issueId,
      'issueKey' => $issueKey,
    ];
  }
}

