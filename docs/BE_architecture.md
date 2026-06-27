backend/
├── public/
│   └── api/
│       └── inquiries.php (エントリーポイント：薄いコントローラー)
├── src/
│   ├── Controllers/
│   │   └── InquiryController.php (リクエストの交通整理)
│   ├── Services/
│   │   ├── InquiryService.php (ビジネスロジック：DB保存や外部連携の調整)
│   │   ├── BacklogService.php (Backlog操作専門)
│   │   └── SlackService.php (Slack通知専門)
│   ├── Repositories/
│   │   └── InquiryRepository.php (DB操作専門)
│   ├── Models/
│   │   └── Inquiry.php (データの構造体)
│   └── Utils/
│       └── HttpClient.php (cURL処理の共通化)
└── bootstrap.php (オートローダーや環境変数の初期化)
