-- =====================================================
-- Order Sütunları Migration
-- Bu dosyayı phpMyAdmin'den çalıştırın
-- =====================================================

-- Menu Items tablosuna order sütunu ekle (eğer yoksa)
-- NOT: IF NOT EXISTS sadece MariaDB 10.0.2+ ve MySQL 8.0+ destekler
-- Eğer hata alırsanız aşağıdaki alternatifi kullanın

-- Yöntem 1: MariaDB/MySQL 8.0+ için
ALTER TABLE `menu_items` ADD COLUMN IF NOT EXISTS `order` INT(11) DEFAULT 0 COMMENT 'Sıralama' AFTER `type_id`;

-- Yöntem 2: Eski MySQL için (hata verirse yorum satırı yapıp bunu kullanın)
-- ALTER TABLE `menu_items` ADD COLUMN `order` INT(11) DEFAULT 0 COMMENT 'Sıralama' AFTER `type_id`;

-- Form Fields tablosuna order sütunu ekle (eğer yoksa)  
ALTER TABLE `form_fields` ADD COLUMN IF NOT EXISTS `order` INT(11) DEFAULT 0 COMMENT 'Sıralama';

-- Post Categories tablosuna order sütunu ekle (eğer yoksa)
ALTER TABLE `post_categories` ADD COLUMN IF NOT EXISTS `order` INT(11) DEFAULT 0 COMMENT 'Sıralama';

-- Slider Items tablosuna order sütunu ekle (eğer yoksa)
ALTER TABLE `slider_items` ADD COLUMN IF NOT EXISTS `order` INT(11) DEFAULT 0 COMMENT 'Sıralama';

-- Slider Layers tablosuna order sütunu ekle (eğer yoksa)
ALTER TABLE `slider_layers` ADD COLUMN IF NOT EXISTS `order` INT(11) DEFAULT 0 COMMENT 'Sıralama';

-- NOT: modules ve module_permissions tabloları order kullanmaz!
-- modules: order sütunu YOK (name'e göre sıralanır)
-- module_permissions: sort_order sütunu VAR (zaten doğru)
