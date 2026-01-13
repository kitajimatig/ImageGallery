<?php
require __DIR__ . '/config.php';

$perPage = 12;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1)$page = 1;

$totalStmt = $pdo->query("SELECT COUNT(*) FROM images");
//一列目を取り出す
$totalCount = (int)$totalStmt->fetchColumn();

$totalPages = ($totalCount > 0) ? (int)ceil($totalCount / $perPage) : 1;

if($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT * FROM images ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
//int指定するためにbindValue(executeだと指定できない)
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
$stmt->execute();

$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像ギャラリー</title>
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
            <div class="page-title">みんなの投稿</div>
            <div class="page-sub">
                全 <?php echo htmlspecialchars($totalCount, ENT_QUOTES, 'UTF-8'); ?> 件の画像
            </div>
        </div>
        <div>
            <a class="btn btn-primary" href="upload_image.php">＋ 新しい画像を投稿</a>
        </div>
    </div>

    <section class="gallery-grid">
        <?php foreach ($images as $image): ?>
            <?php
                $id   = (int)$image['id'];
                $path = 'uploads/' . htmlspecialchars($image['filename'], ENT_QUOTES, 'UTF-8');
                $title = htmlspecialchars($image['title'] ?? '', ENT_QUOTES, 'UTF-8');
            ?>
            <article class="image-card image-card--simple">
                <a href="image_detail.php?id=<?php echo $id; ?>">
                    <div class="image-thumb-wrap">
                        <img src="<?php echo $path; ?>"
                            alt="<?php echo $title; ?>">
                    </div>
                </a>
            </article>
        <?php endforeach; ?>

        <?php if (empty($images)): ?>
            <p>まだ画像が投稿されていません。</p>
        <?php endif; ?>
    </section>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; 前へ</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="current"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">次へ &raquo;</a>
        <?php endif; ?>
    </div>

</div><!-- /.container -->

</body>
</html>