<?php
/**
 * Kurulum İşlem Action Dosyası
 * Config dosyası oluşturur, tabloları oluşturur, admin kullanıcı ekler
 */

session_start();

// Session kontrolü
if (!isset($_SESSION['db_host']) || !isset($_SESSION['site_name'])) {
    header("Location: step1.php");
    exit;
}

$messages = [];
$hasError = false;

try {
    // 1. Config dosyası oluştur
    $messages[] = ['type' => 'info', 'text' => 'Config dosyası oluşturuluyor...'];
    
    $configContent = "<?php
/**
 * Veritabanı Bağlantı Ayarları
 * Otomatik kurulum ile oluşturuldu
 */

return [
    'host' => '" . addslashes($_SESSION['db_host']) . "',
    'dbname' => '" . addslashes($_SESSION['db_name']) . "',
    'username' => '" . addslashes($_SESSION['db_user']) . "',
    'password' => '" . addslashes($_SESSION['db_password']) . "',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
";
    
    $configFile = __DIR__ . '/../config/database.php';
    if (!is_dir(dirname($configFile))) {
        mkdir(dirname($configFile), 0755, true);
    }
    
    file_put_contents($configFile, $configContent);
    $messages[] = ['type' => 'success', 'text' => 'Config dosyası oluşturuldu'];
    
    // 2. Veritabanı bağlantısını test et
    $messages[] = ['type' => 'info', 'text' => 'Veritabanı bağlantısı test ediliyor...'];
    require_once __DIR__ . '/../core/Database.php';
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $messages[] = ['type' => 'success', 'text' => 'Veritabanı bağlantısı başarılı'];
    
    // 3. SQL dosyalarını oku ve çalıştır - ÇOK BASIT YÖNTEM
    $messages[] = ['type' => 'info', 'text' => 'Veritabanı tabloları oluşturuluyor...'];
    
    // Önce SET komutlarını çalıştır
    try {
        $connection->exec("SET NAMES utf8mb4");
        $connection->exec("SET FOREIGN_KEY_CHECKS = 0");
    } catch (PDOException $e) {
        // Sessizce devam et
    }
    
    $sqlFiles = [
        __DIR__ . '/schema.sql',
        __DIR__ . '/sliders_schema.sql',
        __DIR__ . '/slider_layers_schema.sql',
        __DIR__ . '/roles_schema.sql'
    ];
    
    $successCount = 0;
    $createdTables = [];
    
    // Her SQL dosyasını işle
    foreach ($sqlFiles as $sqlFile) {
        if (!file_exists($sqlFile)) {
            $messages[] = ['type' => 'error', 'text' => "✗ Dosya bulunamadı: " . basename($sqlFile)];
            continue;
        }
        
        $fileContent = file_get_contents($sqlFile);
        if (empty($fileContent)) {
            continue;
        }
        
        // SQL'i satır satır işle - çok satırlı sorguları doğru şekilde birleştir
        $lines = explode("\n", $fileContent);
        $currentQuery = '';
        $inQuery = false;
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // Yorum satırlarını atla
            if (empty($trimmed) || preg_match('/^--/', $trimmed)) {
                continue;
            }
            
            // SET komutlarını atla
            if (preg_match('/^SET\s+(NAMES|FOREIGN_KEY_CHECKS)/i', $trimmed)) {
                continue;
            }
            
            // Satırı sorguya ekle
            $currentQuery .= $line . "\n";
            
            // CREATE TABLE veya ALTER TABLE ile başlayan sorguları yakala
            if (preg_match('/CREATE\s+TABLE|ALTER\s+TABLE/i', $trimmed)) {
                $inQuery = true;
            }
            
            // Noktalı virgül görünce sorguyu tamamla
            if ($inQuery && strpos($line, ';') !== false) {
                $query = trim($currentQuery);
                if (!empty($query) && strlen($query) > 30) {
                    try {
                        $connection->exec($query);
                        
                        // Tablo adını çıkar
                        if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"]?(\w+)[`"]?/i', $query, $matches)) {
                            $tableName = $matches[1];
                            $createdTables[] = $tableName;
                            $successCount++;
                        } elseif (preg_match('/ALTER\s+TABLE\s+[`"]?(\w+)[`"]?/i', $query, $matches)) {
                            $successCount++;
                        }
                    } catch (PDOException $e) {
                        $errorMsg = $e->getMessage();
                        if (strpos($errorMsg, 'already exists') === false && 
                            strpos($errorMsg, 'Duplicate column') === false &&
                            strpos($errorMsg, 'Duplicate key') === false) {
                            $messages[] = ['type' => 'error', 'text' => "✗ SQL hatası: " . htmlspecialchars(substr($errorMsg, 0, 150))];
                            $hasError = true;
                        } else {
                            // Zaten mevcut, say
                            if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"]?(\w+)[`"]?/i', $query, $matches)) {
                                $createdTables[] = $matches[1];
                            }
                            $successCount++;
                        }
                    }
                }
                
                // Sorguyu sıfırla
                $currentQuery = '';
                $inQuery = false;
            }
        }
    }
    
    // FOREIGN_KEY_CHECKS'i geri aç
    try {
        $connection->exec("SET FOREIGN_KEY_CHECKS = 1");
    } catch (PDOException $e) {
        // Sessizce devam et
    }
    
    if ($successCount > 0) {
        $tableList = !empty($createdTables) ? " (" . implode(', ', array_unique($createdTables)) . ")" : "";
        $messages[] = ['type' => 'success', 'text' => "✓ {$successCount} tablo işlemi tamamlandı{$tableList}"];
    } else {
        $messages[] = ['type' => 'error', 'text' => "✗ Hiç tablo oluşturulamadı!"];
        $hasError = true;
    }
    
    // 4. Varsayılan ayarları ekle (options tablosu varsa)
    if (!$hasError) {
        $messages[] = ['type' => 'info', 'text' => 'Varsayılan ayarlar ekleniyor...'];
        
        try {
            $optionsTableExists = false;
            $result = $connection->query("SHOW TABLES LIKE 'options'");
            if ($result && $result->rowCount() > 0) {
                $optionsTableExists = true;
            }
            
            if ($optionsTableExists) {
                $defaultOptions = [
                    'site_name' => $_SESSION['site_name'],
                    'site_description' => 'İçerik yönetim sistemi',
                    'admin_email' => $_SESSION['admin_email'],
                    'timezone' => 'Europe/Istanbul',
                    'date_format' => 'd/m/Y',
                    'time_format' => 'H:i',
                ];
                
                $optionsAdded = 0;
                foreach ($defaultOptions as $key => $value) {
                    try {
                        $existing = $db->fetch("SELECT option_id FROM options WHERE option_name = ?", [$key]);
                        if (!$existing) {
                            $db->query(
                                "INSERT INTO options (option_name, option_value, autoload) VALUES (?, ?, 'yes')",
                                [$key, $value]
                            );
                            $optionsAdded++;
                        }
                    } catch (PDOException $e) {
                        // Sessizce devam et
                    }
                }
                
                if ($optionsAdded > 0) {
                    $messages[] = ['type' => 'success', 'text' => "✓ {$optionsAdded} varsayılan ayar eklendi"];
                } else {
                    $messages[] = ['type' => 'info', 'text' => 'Varsayılan ayarlar zaten mevcut'];
                }
            } else {
                $messages[] = ['type' => 'info', 'text' => 'Options tablosu bulunamadı, ayarlar atlandı'];
            }
        } catch (Exception $e) {
            $messages[] = ['type' => 'info', 'text' => 'Varsayılan ayarlar eklenirken sorun oluştu, devam ediliyor...'];
        }
    }
    
    // 5. Admin kullanıcısını oluştur
    if (!$hasError) {
        $messages[] = ['type' => 'info', 'text' => 'Admin kullanıcısı oluşturuluyor...'];
        
        try {
            $adminCheck = $db->fetch("SELECT id FROM users WHERE username = ? OR email = ?", [
                $_SESSION['admin_username'],
                $_SESSION['admin_email']
            ]);
        } catch (PDOException $e) {
            // Tablo yok veya hata, admin kullanıcısı oluştur
            $adminCheck = false;
        }
        
        if (!$adminCheck) {
            try {
                $adminPassword = password_hash($_SESSION['admin_password'], PASSWORD_DEFAULT);
                
                // İlk kullanıcı her zaman super_admin olmalı
                // Önce kullanıcı sayısını kontrol et
                $userCount = 0;
                try {
                    $countResult = $db->fetch("SELECT COUNT(*) as count FROM users");
                    $userCount = $countResult['count'] ?? 0;
                } catch (PDOException $e) {
                    // Tablo yok, ilk kullanıcı
                    $userCount = 0;
                }
                
                // İlk kullanıcı ise super_admin, değilse admin
                $role = ($userCount == 0) ? 'super_admin' : 'admin';
                
                $db->query(
                    "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'active')",
                    [$_SESSION['admin_username'], $_SESSION['admin_email'], $adminPassword, $role]
                );
                
                $roleLabel = ($role === 'super_admin') ? 'Süper Admin' : 'Admin';
                $messages[] = ['type' => 'success', 'text' => "{$roleLabel} kullanıcısı oluşturuldu (İlk kullanıcı: {$roleLabel})"];
            } catch (PDOException $e) {
                $messages[] = ['type' => 'error', 'text' => 'Admin kullanıcısı oluşturulamadı: ' . htmlspecialchars($e->getMessage())];
                $hasError = true;
            }
        } else {
            // Mevcut kullanıcı varsa, eğer ilk kullanıcı ise super_admin yap
            try {
                $userCount = 0;
                $countResult = $db->fetch("SELECT COUNT(*) as count FROM users");
                $userCount = $countResult['count'] ?? 0;
                
                if ($userCount == 1) {
                    // İlk kullanıcı, super_admin yap
                    $db->query(
                        "UPDATE users SET role = 'super_admin' WHERE id = ?",
                        [$adminCheck['id']]
                    );
                    $messages[] = ['type' => 'info', 'text' => 'İlk kullanıcı Süper Admin olarak güncellendi'];
                } else {
                    $messages[] = ['type' => 'info', 'text' => 'Admin kullanıcısı zaten mevcut'];
                }
            } catch (PDOException $e) {
                $messages[] = ['type' => 'info', 'text' => 'Admin kullanıcısı zaten mevcut'];
            }
        }
    }
    
    // Başarılı!
    // Mesajları göster ve step3'e yönlendir
    foreach ($messages as $msg) {
        echo '<div class="status-item ' . htmlspecialchars($msg['type']) . '">';
        echo '<span class="status-icon">' . ($msg['type'] === 'success' ? '✓' : ($msg['type'] === 'error' ? '✗' : 'ℹ')) . '</span>';
        echo '<span>' . $msg['text'] . '</span>';
        echo '</div>';
    }
    
    if (!$hasError) {
        echo '<a href="step3.php" class="btn">Kurulumu Tamamla →</a>';
    } else {
        echo '<a href="step2.php" class="btn">Geri Dön ve Tekrar Dene</a>';
    }
    
} catch (Exception $e) {
    $hasError = true;
    echo '<div class="status-item error">';
    echo '<span class="status-icon">✗</span>';
    echo '<span>Hata: ' . htmlspecialchars($e->getMessage()) . '</span>';
    echo '</div>';
    echo '<a href="step2.php" class="btn">Geri Dön</a>';
}
