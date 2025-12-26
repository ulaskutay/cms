-- Gelişmiş Rol Sistemi Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur
-- Güncelleme: 2025-01-01

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- TABLOLAR
-- ============================================

-- Roller tablosu
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `description` text DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT 0 COMMENT 'Sistem rolü mü? (silinemez)',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Varsayılan rol mü?',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `is_system` (`is_system`),
  KEY `is_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rol yetkileri tablosu
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL COMMENT 'Modül adı',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission` (`role_id`, `permission`),
  KEY `role_id` (`role_id`),
  KEY `module` (`module`),
  KEY `permission` (`permission`),
  CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modüller tablosu (modül tanımları için)
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modül yetkileri tablosu (her modül için mevcut yetkiler)
CREATE TABLE IF NOT EXISTS `module_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_permission` (`module_id`, `permission`),
  KEY `module_id` (`module_id`),
  KEY `order` (`order`),
  CONSTRAINT `fk_module_permissions_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- VARSAYILAN SİSTEM ROLLERİ
-- ============================================
INSERT INTO `roles` (`name`, `slug`, `description`, `is_system`, `is_default`) VALUES
('Süper Admin', 'super_admin', 'Tüm yetkilere sahip, sistem yöneticisi', 1, 0),
('Admin', 'admin', 'Yönetim paneli erişimi, çoğu yetkiye sahip', 1, 0),
('Editör', 'editor', 'İçerik yönetimi ve düzenleme yetkisi', 1, 0),
('Yazar', 'author', 'Sadece içerik oluşturma ve düzenleme', 1, 0),
('Kullanıcı', 'user', 'Sınırlı erişim, sadece görüntüleme', 1, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `description`=VALUES(`description`);


-- ============================================
-- TÜM MODÜLLER
-- ============================================
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) VALUES
('users', 'users', 'Kullanıcılar', 'Kullanıcı yönetimi modülü', 'people', 1),
('roles', 'roles', 'Roller', 'Kullanıcı rolleri yönetimi', 'admin_panel_settings', 1),
('posts', 'posts', 'Yazılar', 'Blog yazıları ve içerik yönetimi', 'article', 1),
('media', 'media', 'Medya', 'Dosya ve görsel yönetimi', 'perm_media', 1),
('forms', 'forms', 'Formlar', 'Form oluşturma ve yönetimi', 'dynamic_form', 1),
('agreements', 'agreements', 'Sözleşmeler', 'Yasal sözleşme ve metinler', 'gavel', 1),
('sliders', 'sliders', 'Sliderlar', 'Slider yönetimi modülü', 'slideshow', 1),
('themes', 'themes', 'Temalar', 'Tema yönetimi ve özelleştirme', 'style', 1),
('menus', 'menus', 'Menüler', 'Site menü yönetimi', 'menu', 1),
('smtp', 'smtp', 'SMTP Ayarları', 'E-posta gönderim ayarları', 'mail', 1),
('modules', 'modules', 'Modül Yönetimi', 'Sistem modüllerinin yönetimi', 'extension', 1),
('settings', 'settings', 'Ayarlar', 'Sistem ayarları modülü', 'settings', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);


-- ============================================
-- KULLANICILAR MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'users.view', 'Kullanıcıları Görüntüle', 'Kullanıcı listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'users'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'users.create', 'Kullanıcı Oluştur', 'Yeni kullanıcı oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'users'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'users.edit', 'Kullanıcı Düzenle', 'Kullanıcı bilgilerini düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'users'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'users.delete', 'Kullanıcı Sil', 'Kullanıcı silme yetkisi'
FROM `modules` m WHERE m.slug = 'users'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- ROLLER MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'roles.view', 'Rolleri Görüntüle', 'Rol listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'roles'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'roles.create', 'Rol Oluştur', 'Yeni rol oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'roles'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'roles.edit', 'Rol Düzenle', 'Rol düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'roles'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'roles.delete', 'Rol Sil', 'Rol silme yetkisi'
FROM `modules` m WHERE m.slug = 'roles'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- YAZILAR MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'posts.view', 'Yazıları Görüntüle', 'Yazı listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'posts'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'posts.create', 'Yazı Oluştur', 'Yeni yazı oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'posts'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'posts.edit', 'Yazı Düzenle', 'Yazı düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'posts'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'posts.delete', 'Yazı Sil', 'Yazı silme yetkisi'
FROM `modules` m WHERE m.slug = 'posts'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'posts.publish', 'Yazı Yayınla', 'Yazı yayınlama yetkisi'
FROM `modules` m WHERE m.slug = 'posts'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- MEDYA MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'media.view', 'Medya Görüntüle', 'Medya kütüphanesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'media.upload', 'Dosya Yükle', 'Dosya yükleme yetkisi'
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'media.edit', 'Medya Düzenle', 'Medya bilgilerini düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'media.delete', 'Medya Sil', 'Medya silme yetkisi'
FROM `modules` m WHERE m.slug = 'media'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- FORMLAR MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'forms.view', 'Formları Görüntüle', 'Form listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'forms'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'forms.create', 'Form Oluştur', 'Yeni form oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'forms'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'forms.edit', 'Form Düzenle', 'Form düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'forms'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'forms.delete', 'Form Sil', 'Form silme yetkisi'
FROM `modules` m WHERE m.slug = 'forms'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'forms.submissions', 'Form Gönderileri', 'Form gönderilerini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'forms'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- SÖZLEŞMELER MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'agreements.view', 'Sözleşmeleri Görüntüle', 'Sözleşme listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'agreements'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'agreements.create', 'Sözleşme Oluştur', 'Yeni sözleşme oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'agreements'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'agreements.edit', 'Sözleşme Düzenle', 'Sözleşme düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'agreements'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'agreements.delete', 'Sözleşme Sil', 'Sözleşme silme yetkisi'
FROM `modules` m WHERE m.slug = 'agreements'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- SLIDERLAR MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'sliders.view', 'Sliderları Görüntüle', 'Slider listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'sliders'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'sliders.create', 'Slider Oluştur', 'Yeni slider oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'sliders'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'sliders.edit', 'Slider Düzenle', 'Slider bilgilerini düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'sliders'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'sliders.delete', 'Slider Sil', 'Slider silme yetkisi'
FROM `modules` m WHERE m.slug = 'sliders'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- TEMALAR MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'themes.view', 'Temaları Görüntüle', 'Tema listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'themes'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'themes.activate', 'Tema Aktifleştir', 'Tema aktifleştirme yetkisi'
FROM `modules` m WHERE m.slug = 'themes'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'themes.customize', 'Tema Özelleştir', 'Tema ayarlarını düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'themes'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'themes.edit_code', 'Tema Kodu Düzenle', 'Tema dosyalarını düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'themes'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'themes.upload', 'Tema Yükle', 'Tema yükleme yetkisi'
FROM `modules` m WHERE m.slug = 'themes'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'themes.delete', 'Tema Sil', 'Tema silme yetkisi'
FROM `modules` m WHERE m.slug = 'themes'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- MENÜLER MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'menus.view', 'Menüleri Görüntüle', 'Menü listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'menus'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'menus.create', 'Menü Oluştur', 'Yeni menü oluşturma yetkisi'
FROM `modules` m WHERE m.slug = 'menus'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'menus.edit', 'Menü Düzenle', 'Menü düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'menus'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'menus.delete', 'Menü Sil', 'Menü silme yetkisi'
FROM `modules` m WHERE m.slug = 'menus'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- SMTP MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'smtp.view', 'SMTP Ayarlarını Görüntüle', 'SMTP ayarlarını görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'smtp'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'smtp.edit', 'SMTP Ayarlarını Düzenle', 'SMTP ayarlarını düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'smtp'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'smtp.test', 'Test E-postası Gönder', 'Test e-postası gönderme yetkisi'
FROM `modules` m WHERE m.slug = 'smtp'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- MODÜL YÖNETİMİ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'modules.view', 'Modülleri Görüntüle', 'Modül listesini görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'modules'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'modules.manage', 'Modülleri Yönet', 'Modül yönetim yetkisi'
FROM `modules` m WHERE m.slug = 'modules'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- AYARLAR MODÜLÜ YETKİLERİ
-- ============================================
INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'settings.view', 'Ayarları Görüntüle', 'Sistem ayarlarını görüntüleme yetkisi'
FROM `modules` m WHERE m.slug = 'settings'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);

INSERT INTO `module_permissions` (`module_id`, `permission`, `label`, `description`) 
SELECT m.id, 'settings.edit', 'Ayarları Düzenle', 'Sistem ayarlarını düzenleme yetkisi'
FROM `modules` m WHERE m.slug = 'settings'
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`);


-- ============================================
-- SÜPER ADMİN ROLÜNE TÜM YETKİLERİ VER
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'super_admin'
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


-- ============================================
-- ADMİN ROLÜNE YETKİLERİ VER
-- (modules ve roles hariç)
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'admin' 
  AND m.slug NOT IN ('modules', 'roles')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


-- ============================================
-- EDİTÖR ROLÜNE YETKİLERİ VER
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'editor' 
  AND m.slug IN ('posts', 'media', 'forms', 'agreements', 'sliders', 'menus')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


-- ============================================
-- YAZAR ROLÜNE YETKİLERİ VER
-- ============================================
-- Yazılar - görüntüleme, oluşturma, düzenleme
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'author' 
  AND m.slug = 'posts'
  AND mp.permission IN ('posts.view', 'posts.create', 'posts.edit')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);

-- Medya - görüntüleme ve yükleme
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'author' 
  AND m.slug = 'media'
  AND mp.permission IN ('media.view', 'media.upload')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


-- ============================================
-- KULLANICI ROLÜNE YETKİLERİ VER
-- (Sadece görüntüleme)
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'user' 
  AND mp.permission LIKE '%.view'
  AND m.slug IN ('posts', 'media')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


SET FOREIGN_KEY_CHECKS = 1;


-- ============================================
-- ÖZET BİLGİ
-- ============================================
-- Bu şema şu modülleri içerir:
-- 1. users (Kullanıcılar) - view, create, edit, delete
-- 2. roles (Roller) - view, create, edit, delete
-- 3. posts (Yazılar) - view, create, edit, delete, publish
-- 4. media (Medya) - view, upload, edit, delete
-- 5. forms (Formlar) - view, create, edit, delete, submissions
-- 6. agreements (Sözleşmeler) - view, create, edit, delete
-- 7. sliders (Sliderlar) - view, create, edit, delete
-- 8. themes (Temalar) - view, activate, customize, edit_code, upload, delete
-- 9. menus (Menüler) - view, create, edit, delete
-- 10. smtp (SMTP) - view, edit, test
-- 12. modules (Modül Yönetimi) - view, manage
-- 13. settings (Ayarlar) - view, edit
--
-- Varsayılan yetki atamaları:
-- - super_admin: Tüm yetkiler
-- - admin: modules ve roles hariç tüm yetkiler
-- - editor: posts, media, forms, agreements, sliders, menus yetkileri
-- - author: posts (view, create, edit), media (view, upload)
-- - user: posts, media görüntüleme
