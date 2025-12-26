<?php
/**
 * Kurulum Adım 2: Site ve Admin Bilgileri
 */

session_start();

// Veritabanı bilgileri kontrolü
if (!isset($_SESSION['db_host']) || !isset($_SESSION['db_name'])) {
    header("Location: step1.php");
    exit;
}

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['site_name'] = $_POST['site_name'] ?? '';
    $_SESSION['admin_username'] = $_POST['admin_username'] ?? 'admin';
    $_SESSION['admin_email'] = $_POST['admin_email'] ?? '';
    $_SESSION['admin_password'] = $_POST['admin_password'] ?? '';
    
    // Validasyon
    if (empty($_SESSION['site_name']) || empty($_SESSION['admin_username']) || 
        empty($_SESSION['admin_email']) || empty($_SESSION['admin_password'])) {
        $error = "Lütfen tüm alanları doldurun";
    } elseif (!filter_var($_SESSION['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi girin";
    } elseif (strlen($_SESSION['admin_password']) < 6) {
        $error = "Şifre en az 6 karakter olmalıdır";
    } else {
        // Kurulum işlemini başlat
        header("Location: install_process.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum - Adım 2: Site Bilgileri</title>
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
            background: linear-gradient(to right, #667eea 0%, #667eea 50%, #ddd 50%);
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
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
            <div class="step inactive">
                <div class="step-number">1</div>
                <div style="font-size: 12px; color: #999;">Veritabanı</div>
            </div>
            <div class="step active">
                <div class="step-number">2</div>
                <div style="font-size: 12px; color: #667eea;">Site Bilgileri</div>
            </div>
            <div class="step inactive">
                <div class="step-number">3</div>
                <div style="font-size: 12px; color: #999;">Tamamlandı</div>
            </div>
        </div>

        <h1>Site ve Yönetici Bilgileri</h1>
        <p class="subtitle">Site adınızı ve admin kullanıcı bilgilerinizi girin</p>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="site_name">Site Adı</label>
                <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($_SESSION['site_name'] ?? ''); ?>" required>
                <div class="form-help">Sitenizin görünen adı</div>
            </div>

            <div class="form-group">
                <label for="admin_username">Yönetici Kullanıcı Adı</label>
                <input type="text" id="admin_username" name="admin_username" value="<?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'admin'); ?>" required>
                <div class="form-help">Admin paneline giriş için kullanıcı adı</div>
            </div>

            <div class="form-group">
                <label for="admin_email">Yönetici E-posta</label>
                <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($_SESSION['admin_email'] ?? ''); ?>" required>
                <div class="form-help">Yönetici e-posta adresi</div>
            </div>

            <div class="form-group">
                <label for="admin_password">Yönetici Şifresi</label>
                <input type="password" id="admin_password" name="admin_password" required>
                <div class="form-help">En az 6 karakter</div>
            </div>

            <div class="btn-group">
                <a href="step1.php" class="btn btn-secondary">← Geri</a>
                <button type="submit" class="btn btn-primary">Kurulumu Başlat →</button>
            </div>
        </form>
    </div>
</body>
</html>
