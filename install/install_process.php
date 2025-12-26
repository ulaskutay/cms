<?php
/**
 * Kurulum İşlem Dosyası
 * Veritabanı config oluşturur, tabloları oluşturur, admin kullanıcı ekler
 */

session_start();

// Session kontrolü
if (!isset($_SESSION['db_host']) || !isset($_SESSION['site_name'])) {
    header("Location: step1.php");
    exit;
}

// Hata raporlamayı aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum Devam Ediyor...</title>
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
            max-width: 700px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
        }
        .status-item {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .status-item.success {
            background: #d5f4e6;
            color: #27ae60;
        }
        .status-item.error {
            background: #fadbd8;
            color: #e74c3c;
        }
        .status-item.info {
            background: #ebf5fb;
            color: #3498db;
        }
        .status-icon {
            font-size: 24px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            background: #667eea;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            margin-top: 30px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .loading {
            text-align: center;
            padding: 40px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h1>🚀 Kurulum Devam Ediyor...</h1>
        <div id="status-container">
            <?php
            // Kurulum işlemini başlat
            require_once __DIR__ . '/install_process_action.php';
            ?>
        </div>
    </div>
</body>
</html>
