-- Sayfalar Sistemi Veritabanı Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Sayfa Özel Alanları (Meta) Tablosu
CREATE TABLE IF NOT EXISTS `page_meta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) NOT NULL COMMENT 'posts tablosundaki sayfa ID (type=page)',
  `meta_key` varchar(255) NOT NULL COMMENT 'Özel alan anahtarı',
  `meta_value` longtext DEFAULT NULL COMMENT 'Özel alan değeri',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `meta_key` (`meta_key`),
  UNIQUE KEY `page_meta_unique` (`page_id`, `meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Foreign key constraint (posts tablosuna referans)
-- Not: posts tablosu zaten var, bu yüzden sadece index kullanıyoruz
-- Eğer posts tablosunda type='page' olan kayıtlar varsa, onları sayfa olarak kabul edeceğiz

SET FOREIGN_KEY_CHECKS = 1;

