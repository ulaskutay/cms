-- Menü Sistemi Veritabanı Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Menus Tablosu (Menü grupları)
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Menü adı (Admin panelde görünür)',
  `location` varchar(100) NOT NULL COMMENT 'Menü konumu (header, footer, sidebar vb.)',
  `description` text DEFAULT NULL COMMENT 'Menü açıklaması',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`location`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu Items Tablosu (Menü öğeleri)
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT 'Bağlı olduğu menü',
  `parent_id` int(11) DEFAULT NULL COMMENT 'Üst menü öğesi (nested menü için)',
  `title` varchar(255) NOT NULL COMMENT 'Menü öğesi başlığı',
  `url` varchar(500) DEFAULT '#' COMMENT 'Link URL',
  `target` enum('_self','_blank') DEFAULT '_self' COMMENT 'Link hedefi',
  `icon` varchar(100) DEFAULT NULL COMMENT 'İkon sınıfı (opsiyonel)',
  `css_class` varchar(255) DEFAULT NULL COMMENT 'Özel CSS sınıfı',
  `type` enum('custom','page','post','category') DEFAULT 'custom' COMMENT 'Link tipi',
  `type_id` int(11) DEFAULT NULL COMMENT 'Page/Post/Category ID (type custom değilse)',
  `order` int(11) DEFAULT 0 COMMENT 'Sıralama',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`),
  KEY `order` (`order`),
  CONSTRAINT `fk_menu_items_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_items_parent` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan Header Menüsü
INSERT INTO `menus` (`name`, `location`, `description`, `status`) VALUES 
('Ana Menü', 'header', 'Sitenin üst kısmında görünen ana navigasyon menüsü', 'active');

SET FOREIGN_KEY_CHECKS = 1;

