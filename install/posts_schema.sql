-- Yazılar (Blog) Sistemi Veritabanı Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Yazı Kategorileri Tablosu
CREATE TABLE IF NOT EXISTS `post_categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alt kategori için foreign key ekle
ALTER TABLE `post_categories` ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Yazı Etiketleri Tablosu
CREATE TABLE IF NOT EXISTS `post_tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Yazılar Tablosu
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `slug` varchar(500) NOT NULL,
  `excerpt` text DEFAULT NULL COMMENT 'Kısa özet',
  `content` longtext DEFAULT NULL COMMENT 'Yazı içeriği',
  `featured_image` varchar(500) DEFAULT NULL COMMENT 'Öne çıkan görsel URL',
  `category_id` bigint(20) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `status` enum('draft','published','scheduled','trash') DEFAULT 'draft',
  `visibility` enum('public','private','password') DEFAULT 'public',
  `password` varchar(255) DEFAULT NULL COMMENT 'Şifreli yazı için',
  `published_at` datetime DEFAULT NULL COMMENT 'Yayın tarihi',
  `views` int(11) DEFAULT 0 COMMENT 'Görüntülenme sayısı',
  `allow_comments` tinyint(1) DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'SEO başlık',
  `meta_description` varchar(500) DEFAULT NULL COMMENT 'SEO açıklama',
  `meta_keywords` varchar(500) DEFAULT NULL COMMENT 'SEO anahtar kelimeler',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `author_id` (`author_id`),
  KEY `status` (`status`),
  KEY `published_at` (`published_at`),
  KEY `visibility` (`visibility`),
  FULLTEXT KEY `search_index` (`title`, `excerpt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Posts foreign keys
ALTER TABLE `posts` ADD CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `posts` ADD CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Yazı-Etiket İlişki Tablosu
CREATE TABLE IF NOT EXISTS `post_tag_relations` (
  `post_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Post-Tag relations foreign keys
ALTER TABLE `post_tag_relations` ADD CONSTRAINT `fk_ptr_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `post_tag_relations` ADD CONSTRAINT `fk_ptr_tag` FOREIGN KEY (`tag_id`) REFERENCES `post_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Varsayılan kategori ekle
INSERT IGNORE INTO `post_categories` (`id`, `name`, `slug`, `description`, `status`) VALUES
(1, 'Genel', 'genel', 'Genel yazılar kategorisi', 'active');

SET FOREIGN_KEY_CHECKS = 1;

