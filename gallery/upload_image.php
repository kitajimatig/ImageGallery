<?php
session_start();

require __DIR__ . '/config.php';
$uploadDir = __DIR__ . '/uploads/';
$message = '';

if(!isset($_SESSION['login'])){
    header("Location: login_image.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'] ?? '';
    $desc = $_POST['desc'] ?? '';

    if($title === '' || !isset($_FILES['img'])){
        $message = 'タイトルと画像は必須です';
    }
    else if($_FILES['img']['error'] !== UPLOAD_ERR_OK){
        $message = 'アップロードエラー';
    }
    else{
        $tmpName = $_FILES['img']['tmp_name'];
        $oriName = $_FILES['img']['name'];

        $ext = strtolower(pathinfo($oriName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if(!in_array($ext, $allowed, true)){
            $message = 'jpg / jpeg / png 以外はアップロードできません';
        }
        else{
            $newName = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath = $uploadDir . $newName;

            if(move_uploaded_file($tmpName, $destPath)){
                $userId = $_SESSION['user_id'];

                $stmt = $pdo->prepare(
                    "INSERT INTO images (filename, title, description, user_id)
                     VALUES (:filename, :title, :description, :user_id)"
                );
                $stmt->execute([
                    ':filename' => $newName,
                    ':title' => $title,
                    ':description' => $desc,
                    ':user_id' => $userId,
                ]);

                $message = 'アップロード成功';
                header("location: gallery_image.php");
            }
            else{
                $message = '保存失敗';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像を投稿 - 画像投稿ギャラリー</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

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
        <div class="page-title">画像を投稿する</div>
        <div class="page-sub">
            タイトルや説明文を添えて、あなたの作品をシェアしましょう。
        </div>
    </div>

    <div class="detail-card">

        <?php if ($message !== ''): ?>
            <p class="<?php echo (mb_strpos($message, '成功') !== false || mb_strpos($message, 'しました') !== false) ? 'message-success' : 'message-error'; ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" action="upload_image.php">

            <div style="margin-bottom:12px;">
                <label for="title" class="field-label">タイトル</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                >
            </div>

            <div style="margin-bottom:12px;">
                <label for="desc" class="field-label">説明文</label>
                <textarea
                    id="desc"
                    name="desc"
                    rows="4"
                ><?php echo isset($_POST['desc']) ? htmlspecialchars($_POST['desc'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </div>

            <div style="margin-bottom:16px;">
                <label for="img" class="field-label">
                    画像ファイル <span style="color:#ef4444; font-size:12px; font-weight:normal;">※必須</span>
                </label>
                <input
                    type="file"
                    id="img"
                    name="img"
                    accept="image/*"
                    required
                >
                <p style="font-size:12px; color:#6b7280; margin-top:4px;">
                    対応形式：JPG / PNG / GIF など
                </p>
            </div>

            <div style="display:flex; justify-content:space-between; gap:8px; flex-wrap:wrap; margin-top:8px;">
                <div>
                    <a class="btn btn-outline" href="gallery_image.php">← ギャラリーに戻る</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        この内容で投稿する
                    </button>
                </div>
            </div>

        </form>
    </div>

</div><!-- /.container -->

</body>
</html>
