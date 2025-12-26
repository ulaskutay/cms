-- Yeni Modüller Migration
-- CMS için eklenen yeni modül ve yetki tanımları
-- Tarih: 2025-01-01
-- NOT: order sütunu opsiyoneldir, yoksa da çalışır

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- YENİ MODÜLLER EKLE
-- ============================================

-- Yazılar modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('posts', 'posts', 'Yazılar', 'Blog yazıları ve içerik yönetimi', 'article', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Medya modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('media', 'media', 'Medya', 'Dosya ve görsel yönetimi', 'perm_media', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Formlar modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('forms', 'forms', 'Formlar', 'Form oluşturma ve yönetimi', 'dynamic_form', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Sözleşmeler modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('agreements', 'agreements', 'Sözleşmeler', 'Yasal sözleşme ve metinler', 'gavel', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Temalar modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('themes', 'themes', 'Temalar', 'Tema yönetimi ve özelleştirme', 'style', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Menüler modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('menus', 'menus', 'Menüler', 'Site menü yönetimi', 'menu', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- SMTP modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('smtp', 'smtp', 'SMTP Ayarları', 'E-posta gönderim ayarları', 'mail', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Modül Yönetimi modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('modules', 'modules', 'Modül Yönetimi', 'Sistem modüllerinin yönetimi', 'extension', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);

-- Roller modülü
INSERT INTO `modules` (`name`, `slug`, `label`, `description`, `icon`, `is_active`) 
VALUES ('roles', 'roles', 'Roller', 'Kullanıcı rolleri yönetimi', 'admin_panel_settings', 1)
ON DUPLICATE KEY UPDATE `label`=VALUES(`label`), `description`=VALUES(`description`);


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
-- SÜPER ADMİN ROLÜNE YENİ YETKİLERİ VER
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'super_admin'
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


-- ============================================
-- ADMİN ROLÜNE YENİ YETKİLERİ VER
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
-- EDİTÖR ROLÜNE YETKİLER
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'editor' 
  AND m.slug IN ('posts', 'media', 'forms', 'agreements', 'menus', 'sliders')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);


-- ============================================
-- YAZAR ROLÜNE YETKİLER
-- ============================================
INSERT INTO `role_permissions` (`role_id`, `permission`, `module`)
SELECT r.id, mp.permission, m.slug
FROM `roles` r
CROSS JOIN `modules` m
JOIN `module_permissions` mp ON mp.module_id = m.id
WHERE r.slug = 'author' 
  AND m.slug = 'posts'
  AND mp.permission IN ('posts.view', 'posts.create', 'posts.edit')
ON DUPLICATE KEY UPDATE `permission`=VALUES(`permission`);

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
-- KULLANICI ROLÜNE YETKİLER
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
-- Bu migration şu modülleri ekler:
-- 1. posts (Yazılar) - view, create, edit, delete, publish
-- 2. media (Medya) - view, upload, edit, delete
-- 3. forms (Formlar) - view, create, edit, delete, submissions
-- 4. agreements (Sözleşmeler) - view, create, edit, delete
-- 5. themes (Temalar) - view, activate, customize, edit_code, upload, delete
-- 6. menus (Menüler) - view, create, edit, delete
-- 7. smtp (SMTP) - view, edit, test
-- 8. modules (Modül Yönetimi) - view, manage
-- 9. roles (Roller) - view, create, edit, delete
--
-- Varsayılan yetki atamaları:
-- - super_admin: Tüm yetkiler
-- - admin: modules ve roles hariç tüm yetkiler
-- - editor: posts, media, forms, agreements, menus, sliders yetkileri
-- - author: posts (view, create, edit), media (view, upload)
-- - user: posts, media görüntüleme
