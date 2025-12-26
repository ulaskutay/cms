-- İçerik Kütüphanesi (Media Library) Veritabanı Şeması
-- Bu dosyayı çalıştırarak medya tablosunu oluşturabilirsiniz

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Medya dosyaları tablosu
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_size` bigint(20) DEFAULT 0,
  `file_path` varchar(500) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `mime_type` (`mime_type`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Media modülünü modules tablosuna ekle
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `order`, `is_active`) VALUES
('media', 'media', 'İçerik Kütüphanesi', 'Medya dosyaları yönetimi modülü', 'perm_media', 2, 1)
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`), `description` = VALUES(`description`);

-- Media modülü yetkilerini module_permissions tablosuna ekle
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `order`) 
SELECT m.id, 'media.view', 'Medyaları Görüntüle', 'Medya dosyalarını görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `order`) 
SELECT m.id, 'media.create', 'Medya Yükle', 'Yeni medya dosyası yükleme yetkisi', 2
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `order`) 
SELECT m.id, 'media.edit', 'Medya Düzenle', 'Medya dosyası bilgilerini düzenleme yetkisi', 3
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `order`) 
SELECT m.id, 'media.delete', 'Medya Sil', 'Medya dosyası silme yetkisi', 4
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`);

-- Admin ve Süper Admin rollerine medya yetkilerini ver
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, 'media'
FROM `roles` r
CROSS JOIN `module_permissions` mp
CROSS JOIN `modules` m ON mp.module_id = m.id
WHERE r.slug IN ('super_admin', 'admin') AND m.slug = 'media'
ON DUPLICATE KEY UPDATE `permission` = VALUES(`permission`);

-- Editör rolüne medya yetkilerini ver
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, 'media'
FROM `roles` r
CROSS JOIN `module_permissions` mp
CROSS JOIN `modules` m ON mp.module_id = m.id
WHERE r.slug = 'editor' AND m.slug = 'media'
ON DUPLICATE KEY UPDATE `permission` = VALUES(`permission`);

-- Yazar rolüne sadece görüntüleme ve yükleme yetkisi ver
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, 'media'
FROM `roles` r
CROSS JOIN `module_permissions` mp
CROSS JOIN `modules` m ON mp.module_id = m.id
WHERE r.slug = 'author' AND m.slug = 'media' AND mp.permission IN ('media.view', 'media.create')
ON DUPLICATE KEY UPDATE `permission` = VALUES(`permission`);

SET FOREIGN_KEY_CHECKS = 1;
