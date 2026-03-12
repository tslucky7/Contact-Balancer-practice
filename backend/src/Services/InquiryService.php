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
      // 2. Backlog連携
      $result = $this->backlogService->createIssue($inquiry); 
      // 3. Slack連携
      $this->slackService->sendSlackMessage($inquiry);
      // 4. DB更新 (Repository)
      $inquiry->complete(
        (int)$result['id'],
        (string)$result['key']
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
