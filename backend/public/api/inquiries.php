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
use App\Validation\InquirySchemaValidator;
use Throwable;
use Monolog\Logger;
use App\Middleware\SecurityHeader;
use App\Middleware\CsrfGuard;

$repository = new InquiryRepository();
$httpClient = new HttpClient();
$backlogService = new BacklogService($httpClient);
$slackService = new SlackService();
$logger = new Logger('InquiryService');
$inquiryService = new InquiryService($repository, $backlogService, $slackService, $logger);
$validator = new InquirySchemaValidator();
$controller = new InquiryController($inquiryService, $validator, $logger);
$securityHeader = new SecurityHeader();
$securityHeader->sendHeaders();
$csrfGuard = new CsrfGuard($logger);
$csrfGuard->check();
try {
  $controller->handleRequest();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'error' => 'Internal Server Error',
  ], JSON_UNESCAPED_UNICODE);
}
