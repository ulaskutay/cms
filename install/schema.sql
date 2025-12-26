-- WordPress Tarzı CMS Veritabanı Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

-- Veritabanı karakter seti
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Kullanıcılar tablosu
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `role` enum('admin','editor','author','subscriber') DEFAULT 'subscriber',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sistem ayarları tablosu
CREATE TABLE IF NOT EXISTS `options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) NOT NULL,
  `option_value` longtext,
  `autoload` enum('yes','no') DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- İçerikler tablosu (gelecek için)
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext,
  `excerpt` text,
  `status` enum('draft','publish','private','trash') DEFAULT 'draft',
  `type` enum('post','page') DEFAULT 'post',
  `featured_image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `author_id` (`author_id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `created_at` (`created_at`),
  FULLTEXT KEY `title_content` (`title`,`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Medya dosyaları tablosu (gelecek için)
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
  KEY `mime_type` (`mime_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

