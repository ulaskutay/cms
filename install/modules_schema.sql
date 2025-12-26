-- Modül Sistemi Veritabanı Şeması
-- WordPress tarzı modül sistemi için güncellenmiş tablo yapısı
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Önce foreign key bağlı tabloları sil (sıra önemli!)
DROP TABLE IF EXISTS `module_logs`;
DROP TABLE IF EXISTS `module_routes`;
DROP TABLE IF EXISTS `module_hooks`;
DROP TABLE IF EXISTS `module_meta`;
DROP TABLE IF EXISTS `module_permissions`;

-- Yeni modüller tablosu
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Modül teknik adı (klasör adı)',
  `slug` varchar(100) NOT NULL COMMENT 'URL-friendly slug',
  `label` varchar(150) NOT NULL COMMENT 'Görünen ad',
  `description` text DEFAULT NULL COMMENT 'Modül açıklaması',
  `version` varchar(20) DEFAULT '1.0.0' COMMENT 'Modül versiyonu',
  `author` varchar(100) DEFAULT NULL COMMENT 'Geliştirici adı',
  `author_url` varchar(255) DEFAULT NULL COMMENT 'Geliştirici website',
  `icon` varchar(50) DEFAULT 'extension' COMMENT 'Material icon adı',
  `path` varchar(255) DEFAULT NULL COMMENT 'Modül klasör yolu',
  `main_file` varchar(100) DEFAULT 'Controller.php' COMMENT 'Ana controller dosyası',
  `settings` JSON DEFAULT NULL COMMENT 'Modül ayarları (JSON)',
  `dependencies` JSON DEFAULT NULL COMMENT 'Bağımlılıklar (JSON)',
  `requires_php` varchar(10) DEFAULT '7.4' COMMENT 'Minimum PHP versiyonu',
  `requires_cms` varchar(10) DEFAULT '1.0' COMMENT 'Minimum CMS versiyonu',
  `is_active` tinyint(1) DEFAULT 0 COMMENT 'Aktif mi?',
  `is_system` tinyint(1) DEFAULT 0 COMMENT 'Sistem modülü mü? (silinemez)',
  `has_settings` tinyint(1) DEFAULT 0 COMMENT 'Ayarlar sayfası var mı?',
  `has_frontend` tinyint(1) DEFAULT 0 COMMENT 'Frontend route var mı?',
  `has_admin` tinyint(1) DEFAULT 1 COMMENT 'Admin paneli var mı?',
  `has_widgets` tinyint(1) DEFAULT 0 COMMENT 'Widget içeriyor mu?',
  `has_shortcodes` tinyint(1) DEFAULT 0 COMMENT 'Shortcode içeriyor mu?',
  `menu_position` int(11) DEFAULT 100 COMMENT 'Admin menü sırası',
  `installed_at` datetime DEFAULT NULL COMMENT 'Kurulum tarihi',
  `activated_at` datetime DEFAULT NULL COMMENT 'Son aktivasyon tarihi',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Son güncelleme',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Oluşturulma tarihi',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`),
  KEY `is_active` (`is_active`),
  KEY `is_system` (`is_system`),
  KEY `menu_position` (`menu_position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modül yetkileri tablosu (güncelle)
DROP TABLE IF EXISTS `module_permissions`;
CREATE TABLE `module_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL COMMENT 'Yetki kodu (örn: posts.create)',
  `label` varchar(150) NOT NULL COMMENT 'Görünen ad',
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Varsayılan olarak verilsin mi?',
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_permission` (`module_id`, `permission`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `fk_module_permissions_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modül meta tablosu (ek veriler için)
CREATE TABLE IF NOT EXISTS `module_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `meta_key` varchar(100) NOT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_meta_key` (`module_id`, `meta_key`),
  KEY `module_id` (`module_id`),
  KEY `meta_key` (`meta_key`),
  CONSTRAINT `fk_module_meta_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modül hooks tablosu (kayıtlı hook'lar)
CREATE TABLE IF NOT EXISTS `module_hooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `hook_type` enum('action','filter') NOT NULL DEFAULT 'action',
  `hook_name` varchar(100) NOT NULL COMMENT 'Hook adı',
  `callback` varchar(255) NOT NULL COMMENT 'Callback fonksiyon/metod',
  `priority` int(11) DEFAULT 10,
  `accepted_args` int(11) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `hook_name` (`hook_name`),
  KEY `hook_type` (`hook_type`),
  CONSTRAINT `fk_module_hooks_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modül route'ları tablosu
CREATE TABLE IF NOT EXISTS `module_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `route_type` enum('admin','frontend','api') NOT NULL DEFAULT 'frontend',
  `method` varchar(10) DEFAULT 'GET' COMMENT 'HTTP method',
  `path` varchar(255) NOT NULL COMMENT 'Route path (örn: products/{id})',
  `handler` varchar(255) NOT NULL COMMENT 'Controller@method',
  `middleware` varchar(255) DEFAULT NULL COMMENT 'Middleware listesi (virgülle ayrılmış)',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `route_type` (`route_type`),
  KEY `path` (`path`),
  CONSTRAINT `fk_module_routes_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modül logları tablosu (aktivite takibi)
CREATE TABLE IF NOT EXISTS `module_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL COMMENT 'installed, activated, deactivated, updated, deleted, error',
  `message` text DEFAULT NULL,
  `data` JSON DEFAULT NULL COMMENT 'Ek veriler',
  `user_id` int(11) DEFAULT NULL COMMENT 'İşlemi yapan kullanıcı',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `action` (`action`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_module_logs_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan sistem modüllerini ekle
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`, `is_system`, `has_admin`, `menu_position`, `installed_at`, `created_at`) VALUES
('posts', 'posts', 'Yazılar', 'Blog yazıları ve içerik yönetimi', 'article', 1, 1, 1, 10, NOW(), NOW()),
('sliders', 'sliders', 'Sliderlar', 'Slider ve banner yönetimi', 'slideshow', 1, 1, 1, 20, NOW(), NOW()),
('menus', 'menus', 'Menüler', 'Navigasyon menü yönetimi', 'menu', 1, 1, 1, 30, NOW(), NOW()),
('forms', 'forms', 'Formlar', 'Form oluşturucu ve yönetimi', 'dynamic_form', 1, 1, 1, 40, NOW(), NOW()),
('media', 'media', 'Medya', 'Dosya ve görsel kütüphanesi', 'perm_media', 1, 1, 1, 50, NOW(), NOW()),
('users', 'users', 'Kullanıcılar', 'Kullanıcı ve rol yönetimi', 'people', 1, 1, 1, 60, NOW(), NOW()),
('design', 'design', 'Tasarım', 'Frontend tasarım düzenleme', 'palette', 1, 1, 1, 70, NOW(), NOW()),
('settings', 'settings', 'Ayarlar', 'Sistem ayarları', 'settings', 1, 1, 1, 100, NOW(), NOW())
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Sistem modüllerinin yetkilerini ekle
-- Posts modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'posts.view', 'Yazıları Görüntüle', 'Yazı listesini görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'posts' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'posts.create', 'Yazı Oluştur', 'Yeni yazı oluşturma yetkisi', 2
FROM `modules` m WHERE m.slug = 'posts' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'posts.edit', 'Yazı Düzenle', 'Yazı düzenleme yetkisi', 3
FROM `modules` m WHERE m.slug = 'posts' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'posts.delete', 'Yazı Sil', 'Yazı silme yetkisi', 4
FROM `modules` m WHERE m.slug = 'posts' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Sliders modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'sliders.view', 'Sliderları Görüntüle', 'Slider listesini görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'sliders' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'sliders.create', 'Slider Oluştur', 'Yeni slider oluşturma yetkisi', 2
FROM `modules` m WHERE m.slug = 'sliders' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'sliders.edit', 'Slider Düzenle', 'Slider düzenleme yetkisi', 3
FROM `modules` m WHERE m.slug = 'sliders' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'sliders.delete', 'Slider Sil', 'Slider silme yetkisi', 4
FROM `modules` m WHERE m.slug = 'sliders' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Menus modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'menus.view', 'Menüleri Görüntüle', 'Menü listesini görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'menus' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'menus.create', 'Menü Oluştur', 'Yeni menü oluşturma yetkisi', 2
FROM `modules` m WHERE m.slug = 'menus' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'menus.edit', 'Menü Düzenle', 'Menü düzenleme yetkisi', 3
FROM `modules` m WHERE m.slug = 'menus' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'menus.delete', 'Menü Sil', 'Menü silme yetkisi', 4
FROM `modules` m WHERE m.slug = 'menus' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Forms modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'forms.view', 'Formları Görüntüle', 'Form listesini görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'forms' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'forms.create', 'Form Oluştur', 'Yeni form oluşturma yetkisi', 2
FROM `modules` m WHERE m.slug = 'forms' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'forms.edit', 'Form Düzenle', 'Form düzenleme yetkisi', 3
FROM `modules` m WHERE m.slug = 'forms' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'forms.delete', 'Form Sil', 'Form silme yetkisi', 4
FROM `modules` m WHERE m.slug = 'forms' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'forms.submissions', 'Gönderileri Görüntüle', 'Form gönderimlerini görüntüleme yetkisi', 5
FROM `modules` m WHERE m.slug = 'forms' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Media modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'media.view', 'Medya Görüntüle', 'Medya kütüphanesini görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'media' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'media.upload', 'Dosya Yükle', 'Dosya yükleme yetkisi', 2
FROM `modules` m WHERE m.slug = 'media' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'media.delete', 'Dosya Sil', 'Dosya silme yetkisi', 3
FROM `modules` m WHERE m.slug = 'media' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Users modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'users.view', 'Kullanıcıları Görüntüle', 'Kullanıcı listesini görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'users' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'users.create', 'Kullanıcı Oluştur', 'Yeni kullanıcı oluşturma yetkisi', 2
FROM `modules` m WHERE m.slug = 'users' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'users.edit', 'Kullanıcı Düzenle', 'Kullanıcı düzenleme yetkisi', 3
FROM `modules` m WHERE m.slug = 'users' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'users.delete', 'Kullanıcı Sil', 'Kullanıcı silme yetkisi', 4
FROM `modules` m WHERE m.slug = 'users' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'users.roles', 'Rolleri Yönet', 'Rol yönetimi yetkisi', 5
FROM `modules` m WHERE m.slug = 'users' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Design modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'design.edit', 'Tasarımı Düzenle', 'Frontend tasarım düzenleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'design' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Settings modülü yetkileri
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'settings.view', 'Ayarları Görüntüle', 'Sistem ayarlarını görüntüleme yetkisi', 1
FROM `modules` m WHERE m.slug = 'settings' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'settings.edit', 'Ayarları Düzenle', 'Sistem ayarlarını düzenleme yetkisi', 2
FROM `modules` m WHERE m.slug = 'settings' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

-- Modül yönetimi yetkisi (sadece super_admin)
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`, `sort_order`) 
SELECT m.id, 'settings.modules', 'Modülleri Yönet', 'Modül kurma/kaldırma yetkisi', 3
FROM `modules` m WHERE m.slug = 'settings' ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

SET FOREIGN_KEY_CHECKS = 1;

