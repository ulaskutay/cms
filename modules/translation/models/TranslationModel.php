<?php
/**
 * Translation Model
 * 
 * Çeviri verilerini yönetir
 */

class TranslationModel {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Tabloları oluştur
     */
    public function createTables() {
        // Diller tablosu
        $sql1 = "CREATE TABLE IF NOT EXISTS `languages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(2) NOT NULL,
            `name` varchar(100) NOT NULL,
            `native_name` varchar(100) NOT NULL,
            `flag` varchar(10) DEFAULT '',
            `is_active` tinyint(1) DEFAULT 1,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `code` (`code`),
            KEY `is_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->query($sql1);
        } catch (Exception $e) {
            error_log("Languages table creation error: " . $e->getMessage());
        }
        
        // Çeviriler tablosu
        $sql2 = "CREATE TABLE IF NOT EXISTS `translations` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `type` varchar(50) NOT NULL,
            `source_id` varchar(32) NOT NULL,
            `source_text` text NOT NULL,
            `target_language` varchar(2) NOT NULL,
            `translated_text` text NOT NULL,
            `auto_translated` tinyint(1) DEFAULT 0,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `source_id` (`source_id`),
            KEY `target_language` (`target_language`),
            KEY `type` (`type`),
            KEY `type_source_target` (`type`, `source_id`, `target_language`),
            KEY `target_source` (`target_language`, `source_id`),
            KEY `source_text_prefix` (`source_text`(255)),
            UNIQUE KEY `unique_translation` (`source_id`, `target_language`, `type`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->query($sql2);
            return true;
        } catch (Exception $e) {
            error_log("Translations table creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tabloları sil
     */
    public function dropTables() {
        try {
            $this->db->query("DROP TABLE IF EXISTS `translations`");
            $this->db->query("DROP TABLE IF EXISTS `languages`");
            return true;
        } catch (Exception $e) {
            error_log("Drop tables error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mevcut tablolara performans index'lerini ekle
     * Bu metod mevcut tablolara yeni index'leri ekler (migration için)
     * NOT: Index'ler createTables() içinde zaten oluşturuluyor, bu metod migration için
     */
    public function addPerformanceIndexes() {
        try {
            // Index'ler zaten createTables() içinde oluşturuluyor
            // Bu metod sadece migration senaryoları için
            // Mevcut index'leri kontrol et ve eksik olanları ekle
            $indexes = [
                "CREATE INDEX IF NOT EXISTS `type_source_target` ON `translations` (`type`, `source_id`, `target_language`)",
                "CREATE INDEX IF NOT EXISTS `target_source` ON `translations` (`target_language`, `source_id`)",
                "CREATE INDEX IF NOT EXISTS `source_text_prefix` ON `translations` (`source_text`(255))"
            ];
            
            foreach ($indexes as $indexSql) {
                try {
                    $this->db->query($indexSql);
                } catch (Exception $e) {
                    // Index zaten varsa hata vermez, sadece log'la
                    error_log("Index creation (may already exist): " . $e->getMessage());
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Add performance indexes error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dil geçerli mi kontrol et
     */
    public function isValidLanguage($code) {
        try {
            $result = $this->db->fetch(
                "SELECT id FROM languages WHERE code = ? AND is_active = 1",
                [$code]
            );
            return !empty($result);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Tüm dilleri getir
     */
    public function getAllLanguages() {
        try {
            return $this->db->fetchAll("SELECT * FROM languages ORDER BY code ASC");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Aktif dilleri getir
     */
    public function getActiveLanguages() {
        try {
            return $this->db->fetchAll("SELECT * FROM languages WHERE is_active = 1 ORDER BY code ASC");
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Koda göre dil getir
     */
    public function getLanguageByCode($code) {
        try {
            return $this->db->fetch("SELECT * FROM languages WHERE code = ?", [$code]);
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Dil ekle
     */
    public function addLanguage($data) {
        try {
            $this->db->query(
                "INSERT INTO languages (code, name, native_name, flag, is_active) VALUES (?, ?, ?, ?, ?)",
                [
                    $data['code'],
                    $data['name'],
                    $data['native_name'] ?? $data['name'],
                    $data['flag'] ?? '',
                    $data['is_active'] ?? 1
                ]
            );
            return true;
        } catch (Exception $e) {
            error_log("Add language error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dil güncelle
     */
    public function updateLanguage($id, $data) {
        try {
            $this->db->query(
                "UPDATE languages SET name = ?, native_name = ?, flag = ?, is_active = ? WHERE id = ?",
                [
                    $data['name'],
                    $data['native_name'] ?? $data['name'],
                    $data['flag'] ?? '',
                    $data['is_active'] ?? 1,
                    $id
                ]
            );
            return true;
        } catch (Exception $e) {
            error_log("Update language error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dil sil
     */
    public function deleteLanguage($id) {
        try {
            $this->db->query("DELETE FROM languages WHERE id = ?", [$id]);
            return true;
        } catch (Exception $e) {
            error_log("Delete language error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Çeviri getir
     * Normalize edilmiş type'lar için fallback desteği: title ve content birbirinin yerine kullanılabilir
     * 
     * @param string $type Çeviri tipi (title, content, vb.)
     * @param string $sourceId Kaynak metnin hash'i (md5)
     * @param string $targetLanguage Hedef dil kodu
     * @param string|null $sourceText Opsiyonel: Kaynak metin (hash eşleşmezse source_text ile arama yapmak için)
     */
    public function getTranslation($type, $sourceId, $targetLanguage, $sourceText = null) {
        try {
            // Önce exact match dene (type + hash + language)
            $translation = $this->db->fetch(
                "SELECT * FROM translations WHERE type = ? AND source_id = ? AND target_language = ?",
                [$type, $sourceId, $targetLanguage]
            );
            
            if ($translation) {
                return $translation;
            }
            
            // Bulunamazsa normalize edilmiş type'lar için fallback dene
            // title ve content birbirinin yerine kullanılabilir
            if ($type === 'title') {
                $translation = $this->db->fetch(
                    "SELECT * FROM translations WHERE type = 'content' AND source_id = ? AND target_language = ?",
                    [$sourceId, $targetLanguage]
                );
            } elseif ($type === 'content') {
                $translation = $this->db->fetch(
                    "SELECT * FROM translations WHERE type = 'title' AND source_id = ? AND target_language = ?",
                    [$sourceId, $targetLanguage]
                );
            }
            
            if ($translation) {
                return $translation;
            }
            
            // Hala bulunamazsa, tüm type'ları dene (son çare - hash ile)
            $translation = $this->db->fetch(
                "SELECT * FROM translations WHERE source_id = ? AND target_language = ? LIMIT 1",
                [$sourceId, $targetLanguage]
            );
            
            if ($translation) {
                return $translation;
            }
            
            // Hash ile bulunamazsa ve sourceText verilmişse, source_text ile ara (eski çeviriler için)
            // NOT: Sadece tam eşleşme için - trim edilmiş sourceText ile karşılaştır
            if ($sourceText !== null && !empty($sourceText)) {
                $trimmedSourceText = trim($sourceText);
                
                // Önce exact type ile ara
                // NOT: TRIM() fonksiyonu index kullanamaz, bu yüzden source_text ile direkt karşılaştırma yapıyoruz
                // Veritabanındaki değerler zaten trim edilmiş olmalı (saveTranslation'da trim ediliyor)
                $translation = $this->db->fetch(
                    "SELECT * FROM translations WHERE source_text = ? AND target_language = ? AND type = ? LIMIT 1",
                    [$trimmedSourceText, $targetLanguage, $type]
                );
                
                if ($translation) {
                    // Eski hash ile kaydedilmiş çeviri bulundu, hash'ini güncelle
                    if ($translation['source_id'] !== $sourceId) {
                        try {
                            $this->db->query(
                                "UPDATE translations SET source_id = ? WHERE id = ?",
                                [$sourceId, $translation['id']]
                            );
                        } catch (Exception $e) {
                            error_log("Hash update error in getTranslation: " . $e->getMessage());
                        }
                    }
                    return $translation;
                }
                
                // Type fallback ile source_text ara (sadece normalize edilmiş type'lar için)
                if ($type === 'title' || $type === 'content') {
                    $fallbackType = ($type === 'title') ? 'content' : 'title';
                    $translation = $this->db->fetch(
                        "SELECT * FROM translations WHERE source_text = ? AND target_language = ? AND type = ? LIMIT 1",
                        [$trimmedSourceText, $targetLanguage, $fallbackType]
                    );
                    
                    if ($translation) {
                        // Hash'ini güncelle
                        if ($translation['source_id'] !== $sourceId) {
                            try {
                                $this->db->query(
                                    "UPDATE translations SET source_id = ? WHERE id = ?",
                                    [$sourceId, $translation['id']]
                                );
                            } catch (Exception $e) {
                                error_log("Hash update error in getTranslation: " . $e->getMessage());
                            }
                        }
                        return $translation;
                    }
                }
                
                // SON ÇARE: Type'ı tamamen ignore et, sadece source_text ile ara
                $translation = $this->db->fetch(
                    "SELECT * FROM translations WHERE source_text = ? AND target_language = ? LIMIT 1",
                    [$trimmedSourceText, $targetLanguage]
                );
                
                if ($translation) {
                    // Hash'ini güncelle
                    if ($translation['source_id'] !== $sourceId) {
                        try {
                            $this->db->query(
                                "UPDATE translations SET source_id = ? WHERE id = ?",
                                [$sourceId, $translation['id']]
                            );
                        } catch (Exception $e) {
                            error_log("Hash update error in getTranslation: " . $e->getMessage());
                        }
                    }
                    return $translation;
                }
            }
            
            return null;
        } catch (Exception $e) {
            error_log("TranslationModel::getTranslation error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * ID'ye göre çeviri getir
     */
    public function getTranslationById($id) {
        try {
            return $this->db->fetch("SELECT * FROM translations WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Çeviri kaydet
     * NOT: Sadece exact type ile kontrol yapıyor (fallback yok) - yanlış eşleşmeleri önlemek için
     */
    public function saveTranslation($data) {
        try {
            // source_text'i trim et (index kullanımı için önemli)
            $data['source_text'] = isset($data['source_text']) ? trim($data['source_text']) : '';
            $data['translated_text'] = isset($data['translated_text']) ? trim($data['translated_text']) : '';
            
            $targetLang = $data['target_language'] ?? null;
            $existing = null;
            
            if (empty($targetLang)) {
                // target_language boş ise sadece source_id ve type ile kontrol et
                $existing = $this->db->fetch(
                    "SELECT * FROM translations WHERE type = ? AND source_id = ? AND (target_language = '' OR target_language IS NULL) LIMIT 1",
                    [$data['type'], $data['source_id']]
                );
            } else {
                // 1. Önce exact match ara (type + hash + target_language)
                $existing = $this->db->fetch(
                    "SELECT * FROM translations WHERE type = ? AND source_id = ? AND target_language = ? LIMIT 1",
                    [$data['type'], $data['source_id'], $targetLang]
                );
                
                // 2. Bulunamazsa, target_language boş olan kaydı ara (extract edilmiş ama çevrilmemiş)
                // Bu kaydı güncelleyeceğiz (target_language ve translated_text ile)
                if (!$existing) {
                    $pendingRecord = $this->db->fetch(
                        "SELECT * FROM translations WHERE type = ? AND source_id = ? AND (target_language = '' OR target_language IS NULL) LIMIT 1",
                        [$data['type'], $data['source_id']]
                    );
                    
                    if ($pendingRecord) {
                        // Bekleyen kaydı güncelle (target_language dahil)
                        $this->db->query(
                            "UPDATE translations SET target_language = ?, translated_text = ?, auto_translated = ?, updated_at = NOW() WHERE id = ?",
                            [
                                $targetLang,
                                $data['translated_text'],
                                $data['auto_translated'] ?? 0,
                                $pendingRecord['id']
                            ]
                        );
                        return $pendingRecord['id'];
                    }
                }
            }
            
            if ($existing) {
                // Güncelle
                $this->db->query(
                    "UPDATE translations SET translated_text = ?, auto_translated = ?, updated_at = NOW() WHERE id = ?",
                    [
                        $data['translated_text'],
                        $data['auto_translated'] ?? 0,
                        $existing['id']
                    ]
                );
                return $existing['id'];
            } else {
                // Yeni ekle
                $this->db->query(
                    "INSERT INTO translations (type, source_id, source_text, target_language, translated_text, auto_translated) 
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [
                        $data['type'],
                        $data['source_id'],
                        $data['source_text'],
                        $data['target_language'],
                        $data['translated_text'],
                        $data['auto_translated'] ?? 0
                    ]
                );
                return $this->db->lastInsertId();
            }
        } catch (Exception $e) {
            error_log("Save translation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Çeviri güncelle
     */
    public function updateTranslation($id, $data) {
        try {
            $this->db->query(
                "UPDATE translations SET translated_text = ?, auto_translated = ?, updated_at = NOW() WHERE id = ?",
                [
                    $data['translated_text'],
                    $data['auto_translated'] ?? 0,
                    $id
                ]
            );
            return true;
        } catch (Exception $e) {
            error_log("Update translation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Çeviri sil
     */
    public function deleteTranslation($id) {
        try {
            $this->db->query("DELETE FROM translations WHERE id = ?", [$id]);
            return true;
        } catch (Exception $e) {
            error_log("Delete translation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Çevirileri listele
     */
    public function getTranslations($page = 1, $perPage = 20, $search = '', $language = '', $type = '') {
        $offset = ($page - 1) * $perPage;
        $where = [];
        $params = [];
        
        if (!empty($search)) {
            $where[] = "(source_text LIKE ? OR translated_text LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($language)) {
            $where[] = "target_language = ?";
            $params[] = $language;
        }
        
        if (!empty($type)) {
            $where[] = "type = ?";
            $params[] = $type;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // MySQL'de LIMIT ve OFFSET placeholder olarak kullanılamaz, integer olarak eklemeliyiz
        $perPage = (int)$perPage;
        $offset = (int)$offset;
        
        try {
            return $this->db->fetchAll(
                "SELECT * FROM translations $whereClause ORDER BY updated_at DESC LIMIT $perPage OFFSET $offset",
                $params
            );
        } catch (Exception $e) {
            error_log("Get translations error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Çeviri sayısı
     */
    public function countTranslations($search = '', $language = '', $type = '') {
        $where = [];
        $params = [];
        
        if (!empty($search)) {
            $where[] = "(source_text LIKE ? OR translated_text LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($language)) {
            $where[] = "target_language = ?";
            $params[] = $language;
        }
        
        if (!empty($type)) {
            $where[] = "type = ?";
            $params[] = $type;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as total FROM translations $whereClause", $params);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * İstatistikler
     */
    public function getStats() {
        try {
            $totalTranslations = $this->db->fetch("SELECT COUNT(*) as total FROM translations");
            $autoTranslations = $this->db->fetch("SELECT COUNT(*) as total FROM translations WHERE auto_translated = 1");
            $totalLanguages = $this->db->fetch("SELECT COUNT(*) as total FROM languages WHERE is_active = 1");
            
            return [
                'total_translations' => $totalTranslations['total'] ?? 0,
                'auto_translations' => $autoTranslations['total'] ?? 0,
                'manual_translations' => ($totalTranslations['total'] ?? 0) - ($autoTranslations['total'] ?? 0),
                'total_languages' => $totalLanguages['total'] ?? 0
            ];
        } catch (Exception $e) {
            return [
                'total_translations' => 0,
                'auto_translations' => 0,
                'manual_translations' => 0,
                'total_languages' => 0
            ];
        }
    }
}
