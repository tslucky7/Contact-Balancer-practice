<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Inquiry;
use App\Repositories\InquiryRepository;
use App\Services\BacklogService;
use App\Services\SlackService;
use Throwable;
use Monolog\Logger;

class InquiryService {
  private InquiryRepository $inquiryRepository;
  private BacklogService $backlogService;
  private SlackService $slackService;
  private Logger $logger;
  public function __construct(
    InquiryRepository $inquiryRepository,
    BacklogService $backlogService,
    SlackService $slackService,
    Logger $logger
  ) {
    $this->inquiryRepository = $inquiryRepository;
    $this->backlogService = $backlogService;
    $this->slackService = $slackService;
    $this->logger = $logger;
  }

  
  public function processInquiry(Inquiry $inquiry): void {
    try {
      // 1. DB保存 (Repository)
      $this->inquiryRepository->create($inquiry);
      // 2. Backlog連携（追って実装）
      // $result = $this->backlogService->createIssue($inquiry); 
      // 3. Slack連携(現状は３つの項目のみ送る。)
      $this->slackService->sendSlackMessage($inquiry);
      // 4. DB更新 (Repository)
      $inquiry->complete(
        // (string)$result['id'],   // 実際の課題ID
        // (string)$result['key']   // 実際の課題キー
        12345,
        "dummyIssueKey"
      );
      $this->inquiryRepository->markSuccess($inquiry);
    } catch (Throwable $e) {
      $inquiry->fail($e->getMessage());
      $this->inquiryRepository->markFailed($inquiry);
      $this->logger->error("InquiryService: Inquiry failed: " . $e->getMessage());
      throw $e;
    }
  }
}
