# FE
- 動的なDOM生成はTypeScriptのみで構築。ライブラリ・フレームワークの使用は無し。
  - node_modulesの使用有り
  - 後にReactへのリプレイスを検討
- todo
  - BEとの入力項目の同期（openAPI?）
## ルーティング
### 入力画面
- path: /
- 初期表示はhtmlで管理
- todo:
  - 入力項目の追加
    - フォームの定義方法の変更
  - ここもSPAで表示
### 確認画面
- path: /confirm
- 動的にDOMを生成
### 完了画面
- path: /complete
- 動的にDOMを生成
- todo: リロードした際にトップへ遷移させる or 画面の状態を維持
  - トップへ戻るボタンの配置
## 関数宣言・アロー関数について
- components: 関数宣言
- handlers: アロー関数

# BE
- 純粋なPHPのみで構築。フレームワークの使用は無し。
  - composerの使用有り
- todo:
  - 最低限のセキュリティをライブラリで導入
  - ファイル分割
  - slack: 項目の変更
  - backlog: API連携
  - BEとの入力項目の同期（openAPI?）

# Infra
- todo: 
  - サーバーの用意

## OpenAPI
Contact-Balancer-practice/
├─ shared/
│  └─ schemas/
│     └─ inquiry.schema.json          # FE/BE共通の入力契約
│
├─ frontend/
│  └─ src/features/inquiry/
│     ├─ adapters/
│     │  └─ formDataAdapter.ts        # readForm/writeForm/loadSession/saveSession
│     ├─ validation/
│     │  └─ inquiryValidator.ts       # schemaを使った検証ラッパ
│     ├─ types/
│     │  └─ types.ts                  # 既存(必要なら生成型に置換)
│     └─ handlers/
│        └─ ...                       # 既存
│
└─ backend/
   └─ src/
      ├─ Controllers/
      │  └─ InquiryController.php     # 受信→validator→factory→service
      ├─ Validation/
      │  ├─ ValidatorInterface.php
      │  └─ InquirySchemaValidator.php
      ├─ Factories/
      │  └─ InquiryFactory.php
      ├─ Models/
      │  └─ Inquiry.php
      └─ Services/
         └─ InquiryService.php
1) まず共通スキーマを作る（真実源を1つに）
- shared/schemas/inquiry.schema.json を作成
- name/email/subject/message の required、maxLength、format: email を定義
- ここは「項目追加時に最初に変更する唯一の場所」にする
2) FEに適用（UX改善）
- toConfirmHandler / submitHandler の送信前で schema 検証
- 既存の formDataAdapter.ts を窓口にして
  - readForm(form) で取得
  - validate(data) で検証
  - OKなら saveSession(data) / API送信
- 目的：再読み込み対応と入力エラー表示を安定化
3) BEに適用（最終防衛線）
- InquiryController::validateRequest() の手書きチェックを薄くする
- ValidatorInterface + InquirySchemaValidator を導入
- コントローラは
  - JSON受信
  - validator実行
  - InquiryFactory で Inquiry 生成
  -InquiryService 呼び出し に限定する
-目的：FEと同じ契約で必ず検証し、二重管理のズレを防ぐ
