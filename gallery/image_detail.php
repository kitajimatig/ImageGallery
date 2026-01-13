<?php
session_start();
require __DIR__ . '/config.php';

if(!isset($_GET['id'])){
    header("Location: gallery_image.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT images.*, users.display_name
     FROM images LEFT JOIN users ON images.user_id = users.id
     WHERE images.id = :id");
$stmt->execute([':id' => $id]);
$image = $stmt->fetch();

if(!$image){
    http_response_code(404);
    echo "画像が見つかりませんでした";
    exit;
}

$loginUserId = $_SESSION['user_id'] ?? null;
$isMine = ($loginUserId !== null && (int)$image['user_id'] === (int)$loginUserId);

$imagePath = "uploads/" . $image['filename'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($image['title'], ENT_QUOTES, 'UTF-8'); ?></title>
</head>

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

    <div class="breadcrumb">
        <a href="gallery_image.php">ギャラリー</a> &gt;
        画像詳細
    </div>

    <div class="detail-layout">
        <main class="detail-main">
            <img src="uploads/<?php echo htmlspecialchars($image['filename'], ENT_QUOTES, 'UTF-8'); ?>"
                 alt="<?php echo htmlspecialchars($image['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </main>

        <aside class="detail-side">
            <div class="detail-card">
                <div class="detail-title">
                    <?php echo htmlspecialchars($image['title'] ?: '無題の作品', ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="detail-meta">
                    投稿者：
                    <?php echo htmlspecialchars($image['display_name'] ?? '名無し', ENT_QUOTES, 'UTF-8'); ?><br>
                    投稿日：
                    <?php echo htmlspecialchars($image['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="detail-desc">
                    <?php echo nl2br(htmlspecialchars($image['description'] ?? '', ENT_QUOTES, 'UTF-8')); ?>
                </div>
            </div>

            <?php if ($isMine): ?>
                <div class="detail-card">
                    <a class="btn btn-danger"
                       href="delete_image.php?id=<?php echo htmlspecialchars($image['id'], ENT_QUOTES, 'UTF-8'); ?>"
                       onclick="return confirm('本当に削除しますか？');">
                        この画像を削除する
                    </a>
                </div>
            <?php endif; ?>

            <div class="detail-card">
                <a class="btn btn-outline" href="gallery_image.php">← ギャラリーに戻る</a>
                <a class="btn btn-outline" href="mypage_image.php">マイページへ</a>
            </div>
        </aside>
    </div>

</div><!-- /.container -->

</body>
</html>