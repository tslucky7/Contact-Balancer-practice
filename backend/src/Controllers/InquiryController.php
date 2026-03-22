<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Factories\InquiryFactory;
use App\Utils\RequestParser;
use App\Services\InquiryService;
use App\Validation\ValidatorInterface;
use Throwable;
use Psr\Log\LoggerInterface;

/**
 * 問い合わせハンドラー
 */
class InquiryController {
  private InquiryService $inquiryService;
  private ValidatorInterface $validator;
  private LoggerInterface $logger;

  public function __construct(
    InquiryService $inquiryService, 
    ValidatorInterface $validator,
    LoggerInterface $logger
  ) {
    $this->inquiryService = $inquiryService;
    $this->validator = $validator;
    $this->logger = $logger;
  }

  /**
   * 
   * - リクエストメソッドがPOSTかどうかを確認
   * - リクエストボディをJSON形式で取得
   * - リクエストボディを検証
   * - リクエストボディを問い合わせオブジェクトに変換
   * - 問い合わせを処理
   * - 問い合わせ結果を返却
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

      $validatedData = $this->validator->validate($data);
      if (!$validatedData['isValid']) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => $validatedData['errors']]);
        exit;
      }

      $inquiry = InquiryFactory::create($data);
      $this->inquiryService->processInquiry($inquiry);

      http_response_code(200);
      echo json_encode(
        [
          'ok' => true, 
          'requestId' => $inquiry->requestId,
          'backlogIssueKey' => $inquiry->getBacklogIssueKey(),
          'name' => $inquiry->name,
          'email' => $inquiry->email,
          'subject' => $inquiry->subject,
          'message' => $inquiry->message,
        ], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
      http_response_code(500);
      echo json_encode(
        [
          'ok' => false, 
          'error' => 'エラーが発生しました。',
        ], JSON_UNESCAPED_UNICODE);
      $this->logger->error($e->getMessage());
      exit;
    }
  }
}
