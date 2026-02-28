<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Inquiry;
use App\Repositories\InquiryRepository;
use App\Services\BacklogService;
use App\Services\SlackService;
use Exception;

class InquiryService {
  private InquiryRepository $inquiryRepository;
  private BacklogService $backlogService;
  private SlackService $slackService;

  public function __construct(
    InquiryRepository $inquiryRepository,
    BacklogService $backlogService,
    SlackService $slackService
  ) {
    $this->inquiryRepository = $inquiryRepository;
    $this->backlogService = $backlogService;
    $this->slackService = $slackService;
  }

  
  public function processInquiry(Inquiry $inquiry): void {
    try {
      // 1. DB保存 (Repository)
      $this->inquiryRepository->create($inquiry);
      // 2. Backlog連携（追って実装）
      // 3. Slack連携(現状は３つの項目のみ送る。)
      $this->slackService->sendSlackMessage($inquiry);
      // 4. DB更新 (Repository)
      $this->inquiryRepository->markSuccess($inquiry);
    } catch (Exception $e) {
      $this->inquiryRepository->markFailed($inquiry);
      throw $e;
    }
  }
}
