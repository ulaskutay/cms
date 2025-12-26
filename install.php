<?php
/**
 * WordPress Tarzı CMS Kurulum Sihirbazı
 * install.php - Ana kurulum giriş sayfası
 */

// Eğer kurulum zaten yapılmışsa ana sayfaya yönlendir
$configFile = __DIR__ . '/config/database.php';
if (file_exists($configFile)) {
    // Hataları yakalayıp kuruluma devam et - Database sınıfını kullanmadan direkt PDO ile
    try {
        // Config dosyasını yükle
        $config = require $configFile;
        
        // Direkt PDO ile bağlantı kur (Database sınıfını kullanmadan)
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT, // Hataları sessizce handle et
        ]);
        
        // Tabloları kontrol et (nazikçe - SHOW TABLES kullan)
        $result = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($result && $result->rowCount() > 0) {
            // Tablo var, kurulum tamamlanmış, ana sayfaya yönlendir (kök dizine)
            header("Location: /");
            exit;
        }
        // Tablo yok, kuruluma devam et
    } catch (Throwable $e) {
        // Herhangi bir hata, kuruluma devam et (hata gösterme)
    }
}

// Kurulum başlangıç sayfası
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Kurulum</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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
        .logo {
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
            font-weight: 600;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 16px 40px;
            background: #667eea;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .info {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        .info strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="logo">🚀</div>
        <h1>CMS Kurulum Sihirbazı</h1>
        <p class="subtitle">WordPress tarzı içerik yönetim sisteminizi kurmak için birkaç dakika ayırın</p>
        
        <a href="install/step1.php" class="btn">Kuruluma Başla →</a>
        
        <div class="info">
            <p><strong>Kurulumdan önce hazır olmanız gerekenler:</strong></p>
            <ul style="text-align: left; margin-top: 10px; padding-left: 20px;">
                <li>Veritabanı adı</li>
                <li>Veritabanı kullanıcı adı</li>
                <li>Veritabanı şifresi</li>
                <li>Site adı</li>
                <li>Admin kullanıcı bilgileri</li>
            </ul>
        </div>
    </div>
</body>
</html>
