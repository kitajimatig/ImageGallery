<?php
session_start();
require __DIR__ . '/config.php';

if(!isset($_SESSION['login'])){
    header("location: login_image.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$displayName = $_SESSION['display_name'];

$stmt = $pdo->prepare("SELECT * FROM images WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $userId]);
$myImages = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
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

    <div class="page-header">
        <div>
            <div class="page-title">
                マイページ
            </div>
            <div class="page-sub">
                ログイン中：<?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?> さん
            </div>
        </div>
        <div>
            <a class="btn btn-primary" href="upload_image.php">＋ 新しい画像を投稿</a>
        </div>
    </div>

    <?php if (!empty($myImages)): ?>
        <section class="gallery-grid">
            <?php foreach ($myImages as $img): ?>
                <?php
                    $id       = (int)$img['id'];
                    $path     = 'uploads/' . htmlspecialchars($img['filename'], ENT_QUOTES, 'UTF-8');
                    $title    = htmlspecialchars($img['title'] ?? '', ENT_QUOTES, 'UTF-8');
                    $created  = htmlspecialchars($img['created_at'] ?? '', ENT_QUOTES, 'UTF-8');
                    $hasTitle = ($title !== '');
                ?>
                <article class="image-card">
                    <a href="image_detail.php?id=<?php echo $id; ?>">
                        <div class="image-thumb-wrap">
                            <img
                                src="<?php echo $path; ?>"
                                alt="<?php echo $title; ?>"
                            >
                        </div>
                    </a>
                    <div class="image-card-body">
                        <h2 class="image-title">
                            <?php echo $hasTitle ? $title : '無題の作品'; ?>
                        </h2>
                        <div class="image-meta">
                            投稿日<br><?php echo $created; ?><br>
                        </div>
                        <div class="image-footer">
                            <a href="image_detail.php?id=<?php echo $id; ?>">
                                詳細を見る →
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <div class="detail-card">
            <p>まだ画像を投稿していません。</p>
            <a class="btn btn-primary" href="upload_image.php">最初の画像を投稿する</a>
        </div>
    <?php endif; ?>

</div><!-- /.container -->

</body>
</html>