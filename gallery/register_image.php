<?php
session_start();
require __DIR__ . '/config.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $displayname = $_POST['display_name'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username === '' || $displayname === '' || $password === ''){
        $message = 'すべての項目に入力してください';
    }
    else{
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $exist = $stmt->fetch();

        if($exist){
            $message = 'そのユーザ名は既に使われています';
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, display_name) VALUES (:username, :password_hash, :display_name)");
            $stmt->execute([
                ':username' => $username,
                ':password_hash' => $hash,
                ':display_name' => $displayname,
            ]);

            $message = "登録完了！";
            header("location: login_image.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<body>
<link rel="stylesheet" href="css/style.css">

<header class="site-header">
    <div class="site-title">画像投稿ギャラリー</div>
    <nav class="site-nav">
        <a href="gallery_image.php">ギャラリー</a>
        <a href="upload_image.php">画像を投稿</a>
        <a href="mypage_image.php">マイページ</a>
        <a href="register_image.php">新規登録</a>
        <a href="login_image.php">ログイン</a>
    </nav>
</header>

<div class="container">

    <div class="page-header">
        <div class="page-title">ユーザ登録</div>
        <div class="page-sub">
            アカウントを作成して、画像の投稿やマイページ機能を利用しましょう。
        </div>
    </div>

    <div class="detail-card">

        <?php if($message !== ''): ?>
            <p class="<?php echo (strpos($message, '登録しました') !== false) ? 'message-info' : 'message-error'; ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form method="post" action="register_image.php">
            <div style="margin-bottom:12px;">
                <label for="username" class="field-label">ユーザ名（ログインID）</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    required
                >
            </div>

            <div style="margin-bottom:12px;">
                <label for="display_name" class="field-label">アカウント名（表示名）</label>
                <input
                    type="text"
                    id="display_name"
                    name="display_name"
                    value="<?php echo isset($_POST['display_name']) ? htmlspecialchars($_POST['display_name'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    required
                >
            </div>

            <div style="margin-bottom:16px;">
                <label for="password" class="field-label">パスワード</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-top:8px; flex-wrap:wrap;">
                <div style="font-size:13px; color:#6b7280;">
                    すでにアカウントをお持ちの方は<br>
                    <a href="login_image.php">ログインはこちら</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        アカウントを作成する
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div style="margin-top:16px; font-size:13px;">
        <a class="btn btn-outline" href="gallery_image.php">← ギャラリーに戻る</a>
    </div>

</div>

</body>
</html>