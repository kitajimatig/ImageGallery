<?php
session_start();
require __DIR__ . '/config.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST["user"] ?? '');
    $pass = $_POST["pass"] ?? '';
    if($user === '' || $pass === ''){
        $message = 'ユーザ名とパスワードを入力してください';
    }
    else{
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if($user && password_verify($pass, $user['password_hash'])){
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['display_name'] = $user['display_name'];

            header("Location: mypage_image.php");
            exit;
        }
        else {
            $message = 'ユーザ名またはパスワードが違います';
        }
    }
}

?>

<!DOCTYPE html>
<html>
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

<div class="container" style="max-width:480px;">

    <div class="page-header">
        <div>
            <div class="page-title">ログイン</div>
            <div class="page-sub">
                アカウントにサインインして、画像の投稿やマイページ機能を利用できます。
            </div>
        </div>
    </div>

    <div class="detail-card">

        <?php if (!empty($errorMessage)): ?>
            <p style="color:#ef4444; font-size:14px; margin-top:0; margin-bottom:12px;">
                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <p style="color:#16a34a; font-size:14px; margin-top:0; margin-bottom:12px;">
                <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form action="login_image.php" method="post">

            <div style="margin-bottom:12px;">
                <label for="text" style="display:block; font-size:14px; font-weight:600; margin-bottom:4px;">
                    ログインID
                </label>
                <input
                    type="text"
                    id="user"
                    name="user"
                    value="<?php echo isset($_POST['user']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #d1d5db; font-size:14px;"
                    required
                >
            </div>

            <div style="margin-bottom:12px;">
                <label for="password" style="display:block; font-size:14px; font-weight:600; margin-bottom:4px;">
                    パスワード
                </label>
                <input
                    type="password"
                    id="pass"
                    name="pass"
                    style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #d1d5db; font-size:14px;"
                    required
                >
            </div>

            <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-top:8px; flex-wrap:wrap;">
                <div style="font-size:13px; color:#6b7280;">
                    アカウントをお持ちでない方は<br>
                    <a href="register_image.php">新規登録はこちら</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        ログインする
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div style="margin-top:16px; font-size:13px;">
        <a class="btn btn-outline" href="gallery_image.php">← ギャラリーに戻る</a>
    </div>

</div><!-- /.container -->

</body>
</html>
