<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\InquiryRepository;
use App\Services\BacklogService;
use App\Services\SlackService;
use Exception;
use App\Utils\IdGenerator;

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

  
  public function processInquiry(array $data): void {
    $requestId = IdGenerator::ulidLike();

    try {
      // 1. DB保存 (Repository)
      $this->inquiryRepository->createInquiry($data);
      // 2. Backlog連携（追って実装）
      // 3. Slack連携(現状は３つの項目のみ送る。)
      $this->slackService->sendSlackMessage($data['subject'], $requestId, $data['issueKey']);
      // 4. DB更新 (Repository)
      $this->inquiryRepository->successUpdateInquiry($data);
    } catch (Exception $e) {
      $this->inquiryRepository->failedUpdateInquiry($data['request_id'], $e->getMessage());
    }
  }
}
