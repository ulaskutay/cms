<?php
/**
 * Kurulum Adım 3: Tamamlandı
 */

session_start();

// Session temizle
$admin_username = $_SESSION['admin_username'] ?? 'admin';
$admin_email = $_SESSION['admin_email'] ?? '';
session_destroy();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum Tamamlandı!</title>
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
            padding: 50px 40px;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        h1 {
            color: #27ae60;
            margin-bottom: 20px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
            line-height: 1.6;
        }
        .credentials {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }
        .credentials h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .credential-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #555;
        }
        .credential-value {
            color: #667eea;
            font-family: monospace;
            background: #fff;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
            text-align: left;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 16px;
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
        <div class="success-icon">✅</div>
        <h1>Kurulum Başarıyla Tamamlandı!</h1>
        <p class="subtitle">
            Tebrikler! CMS sisteminiz kuruldu ve kullanıma hazır.
            Aşağıdaki bilgilerle admin paneline giriş yapabilirsiniz.
        </p>

        <div class="credentials">
            <h3>🔐 Giriş Bilgileri</h3>
            <div class="credential-item">
                <span class="credential-label">Kullanıcı Adı:</span>
                <span class="credential-value"><?php echo htmlspecialchars($admin_username); ?></span>
            </div>
            <?php if ($admin_email): ?>
            <div class="credential-item">
                <span class="credential-label">E-posta:</span>
                <span class="credential-value"><?php echo htmlspecialchars($admin_email); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="warning">
            <strong>⚠️ Güvenlik Uyarısı:</strong><br>
            İlk girişten sonra mutlaka şifrenizi değiştirin!
        </div>

        <div class="btn-group">
            <a href="../public/" class="btn btn-secondary">Ana Sayfa</a>
            <a href="../public/admin.php?page=login" class="btn btn-primary">Admin Paneline Git →</a>
        </div>

        <p style="margin-top: 30px; font-size: 14px; color: #999;">
            <strong>Not:</strong> Güvenlik için <code>install.php</code> ve <code>install/</code> klasörünü silmeyi düşünebilirsiniz.
        </p>
    </div>
</body>
</html>
