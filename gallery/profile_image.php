<?php
session_start();
require __DIR__ . '/config.php';

if(!isset($_SESSION['login'])){
    header("Location: login_image.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, display_name FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();

if (!$user){
    echo "ユーザーが見つかりません。";
    exit;
}

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $displayName = trim($_POST['display_name'] ?? '');

    if($displayName === ''){
        $message = 'アカウント名を入力してください';
    }
    else{
        $stmt = $pdo->prepare("UPDATE users SET display_name = :display_name WHERE id = :id");
        $stmt->execute([
            ':display_name' => $displayName,
            ':id' => $userId,
        ]);

        $_SESSION['display_name'] = $displayName;
        $message = 'プロフィールを更新しました';

        $userId = $_SESSION['user_id'];

        $stmt = $pdo->prepare("UPDATE images SET user_id = :id WHERE user_id IS NULL");
        $stmt->execute([':uid' => $userId]);

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
        <a href="logout_image.php">ログアウト</a>
    </nav>
</header>

<div class="container">

    <div class="page-header">
        <div class="page-title">プロフィール編集</div>
        <div class="page-sub">
            ギャラリーやマイページに表示されるアカウント名を変更できます。
        </div>
    </div>

    <div class="detail-card">

        <?php if ($message !== ''): ?>
            <p class="<?php echo ($message === 'プロフィールを更新しました') ? 'message-success' : 'message-error'; ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form method="post" action="profile_image.php">
            <div style="margin-bottom:12px;">
                <span class="field-label">ログインID（変更不可）</span>
                <div class="static-text">
                    <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label for="display_name" class="field-label">
                    アカウント名（表示名）
                </label>
                <input
                    type="text"
                    id="display_name"
                    name="display_name"
                    value="<?php echo htmlspecialchars($user['display_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="ギャラリーに表示される名前"
                    required
                >
                <p style="font-size:12px; color:#6b7280; margin-top:4px;">
                    画像の投稿者名として表示されます。
                </p>
            </div>

            <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-top:8px; flex-wrap:wrap;">
                <div>
                    <a class="btn btn-outline" href="mypage_image.php">← マイページに戻る</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">変更を保存する</button>
                </div>
            </div>
        </form>

    </div>

</div>

</body>
</html>