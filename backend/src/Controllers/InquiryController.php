<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Utils\RequestParser;
use App\Services\InquiryService;
use Throwable;

/**
 * 問い合わせハンドラー
 */
class InquiryController {
  private InquiryService $inquiryService;

  public function __construct(InquiryService $inquiryService) {
    $this->inquiryService = $inquiryService;
  }

  /**
   * 
   * - リクエストメソッドがPOSTかどうかを確認
   * - リクエストボディをJSON形式で取得
   * @return void
   */
  public function handleRequest(): void {
    // リクエストメソッドがPOSTでなければ、405エラー返却・処理を終了する
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode(['ok' => false, 'error' => 'このAPIはPOSTリクエストのみを受け付けます。']);
      exit;
    }

    try {
      $data = RequestParser::getJsonInput();
      $this->validateRequest($data);

      $this->inquiryService->processInquiry($data);

      http_response_code(200);
      // todo: 要改善：requestIdとbacklogIssueKeyを返却する
      echo json_encode(['ok' => true, 'requestId' => $requestId, 'backlogIssueKey' => $issueKey], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
      http_response_code(500);
      echo json_encode(['ok' => false, 'error' => 'リクエストのパースに失敗しました'], JSON_UNESCAPED_UNICODE);
      exit;
    }
  }

  /**
   * リクエストボディをJSON形式で取得し、必須項目が不足していないかを確認する
   * @param array $data
   * @return void
   */
  private function validateRequest(array $data): void {
    $name = trim((string)($data['name'] ?? ''));
    $email = trim((string)($data['email'] ?? ''));
    $subject = trim((string)($data['subject'] ?? ''));
    $message = trim((string)($data['message'] ?? ''));

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
      http_response_code(400);
      echo json_encode(['ok' => false, 'error' => '必須項目が不足しています']);
      exit;
    }
  }
}
