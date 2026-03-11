<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

use App\Controllers\InquiryController;
use App\Repositories\InquiryRepository;
use App\Services\BacklogService;
use App\Services\InquiryService;
use App\Services\SlackService;
use App\Utils\HttpClient;
use Throwable;
use Monolog\Logger;

$repository = new InquiryRepository();
$httpClient = new HttpClient();
$backlogService = new BacklogService($httpClient);
$slackService = new SlackService();
$logger = new Logger('InquiryService');
$inquiryService = new InquiryService($repository, $backlogService, $slackService, $logger);
$controller = new InquiryController($inquiryService);

try {
  $controller->handleRequest();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'error' => 'Internal Server Error',
  ], JSON_UNESCAPED_UNICODE);
}
