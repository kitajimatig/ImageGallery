# PHP 画像投稿ギャラリー

PHP 学習用に作成した、ユーザー登録・ログイン機能付きのシンプルな画像投稿ギャラリーです。  
MySQL を使用し、画像情報・ユーザー情報を管理しています。

---

## デモ画像
<img height="300" alt="Image" src="https://github.com/user-attachments/assets/f99e4b06-1794-45fa-90cb-09df75497e51" />
<img height="300" alt="Image" src="https://github.com/user-attachments/assets/2334b4b7-b52b-4df6-9745-4f4305ac87fd" />
<img height="300" alt="Image" src="https://github.com/user-attachments/assets/b4aaa154-e743-4bbd-af30-474b0eb2f816" />
<img height="300" alt="Image" src="https://github.com/user-attachments/assets/79436180-1271-4e28-ad5c-f6f5f060ddb8" />
<img height="300" alt="Image" src="https://github.com/user-attachments/assets/8b74b536-ae3f-4eed-977a-1b28db2bf547" />

---

## プロジェクト構成

```
project-root/
├── config.php
├── gallery_image.php
├── image_detail.php
├── upload_image.php
├── delete_image.php
├── mypage_image.php
├── profile_image.php
├── login_image.php
├── register_image.php
├── logout_image.php
├── css/
│   └── style.css
└── uploads/

```

---

## 主な機能

- ユーザー登録 / ログイン / ログアウト
- 画像アップロード
- ギャラリー一覧表示
- 画像詳細表示
- マイページで自分の投稿のみ表示
- 自分が投稿した画像の削除

---

## テーブル

| テーブル名 | 内容 |
|-----------|------|
| users     | ユーザーアカウント（id, password, display_name） |
| images    | 投稿画像（id, user_id, title, caption, filename, created_at） |
---

## 使い方

1. register_image.php でユーザー登録
2. login_image.php でログイン
3. upload_image.php から画像を投稿
4. gallery_image.php で一覧表示
5. 画像クリックで image_detail.php が開く
6. 自分の投稿なら削除ボタンが表示され delete_image.php から削除可能
7. profile_image.php でアカウント名を編集可能

---

## セキュリティ対策

- PDO + プリペアドステートメント
- htmlspecialchars による XSS 対策
- セッションチェック

---

## ライセンス

MIT License

---
