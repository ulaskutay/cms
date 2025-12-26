<?php
/**
 * Kurulum Adım 1: Veritabanı Bağlantı Bilgileri
 */

session_start();

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['db_host'] = $_POST['db_host'] ?? 'localhost';
    $_SESSION['db_name'] = $_POST['db_name'] ?? '';
    $_SESSION['db_user'] = $_POST['db_user'] ?? '';
    $_SESSION['db_password'] = $_POST['db_password'] ?? '';
    
    // Veritabanı bağlantısını test et
    try {
        $dsn = "mysql:host={$_SESSION['db_host']};dbname={$_SESSION['db_name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $_SESSION['db_user'], $_SESSION['db_password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        // Başarılı, bir sonraki adıma geç
        header("Location: step2.php");
        exit;
    } catch (PDOException $e) {
        $error = "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum - Adım 1: Veritabanı</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
        }
        .step.active .step-number {
            background: #667eea;
        }
        .step.inactive .step-number {
            background: #ddd;
        }
        .step-line {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #ddd;
            z-index: 1;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-help {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
        }
        .error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: #fff;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #666;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-secondary:hover {
            background: #d0d0d0;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="step-indicator">
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">1</div>
                <div style="font-size: 12px; color: #667eea;">Veritabanı</div>
            </div>
            <div class="step inactive">
                <div class="step-number">2</div>
                <div style="font-size: 12px; color: #999;">Site Bilgileri</div>
            </div>
            <div class="step inactive">
                <div class="step-number">3</div>
                <div style="font-size: 12px; color: #999;">Tamamlandı</div>
            </div>
        </div>

        <h1>Veritabanı Bağlantı Bilgileri</h1>
        <p class="subtitle">Lütfen veritabanı bilgilerinizi girin</p>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="db_host">Veritabanı Sunucusu</label>
                <input type="text" id="db_host" name="db_host" value="<?php echo htmlspecialchars($_SESSION['db_host'] ?? 'localhost'); ?>" required>
                <div class="form-help">Genellikle "localhost"</div>
            </div>

            <div class="form-group">
                <label for="db_name">Veritabanı Adı</label>
                <input type="text" id="db_name" name="db_name" value="<?php echo htmlspecialchars($_SESSION['db_name'] ?? ''); ?>" required>
                <div class="form-help">cPanel'de oluşturduğunuz veritabanı adı</div>
            </div>

            <div class="form-group">
                <label for="db_user">Veritabanı Kullanıcı Adı</label>
                <input type="text" id="db_user" name="db_user" value="<?php echo htmlspecialchars($_SESSION['db_user'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="db_password">Veritabanı Şifresi</label>
                <input type="password" id="db_password" name="db_password" value="<?php echo htmlspecialchars($_SESSION['db_password'] ?? ''); ?>" required>
            </div>

            <div class="btn-group">
                <a href="../install.php" class="btn btn-secondary">← Geri</a>
                <button type="submit" class="btn btn-primary">İleri →</button>
            </div>
        </form>
    </div>
</body>
</html>
