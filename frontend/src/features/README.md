# FE
- 動的なDOM生成はTypeScriptのみで構築。Reactの使用は無し。
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
- todo:
  - 最低限のセキュリティをライブラリで導入
  - ファイル分割
  - slack: 項目の変更
  - backlog: API連携

# Infra
- todo: 
  - サーバーの用意
