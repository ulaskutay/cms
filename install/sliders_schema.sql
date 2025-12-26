-- Slider Sistemi Veritabanı Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Sliders Tablosu
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `animation_type` enum('fade','slide','zoom','cube','flip','coverflow','cards') DEFAULT 'fade',
  `autoplay` tinyint(1) DEFAULT 1,
  `autoplay_delay` int(11) DEFAULT 5000,
  `navigation` tinyint(1) DEFAULT 1,
  `pagination` tinyint(1) DEFAULT 1,
  `loop` tinyint(1) DEFAULT 1,
  `width` varchar(50) DEFAULT '100%',
  `height` varchar(50) DEFAULT '500px',
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Slider Items Tablosu
CREATE TABLE IF NOT EXISTS `slider_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_id` int(11) NOT NULL,
  `type` enum('image','video','html') DEFAULT 'image',
  `media_url` varchar(500) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(500) DEFAULT NULL,
  `button_target` enum('_self','_blank') DEFAULT '_self',
  `overlay_opacity` decimal(3,2) DEFAULT 0.00,
  `text_position` enum('center','left','right','top','bottom') DEFAULT 'center',
  `order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `slider_id` (`slider_id`),
  KEY `status` (`status`),
  KEY `order` (`order`),
  CONSTRAINT `fk_slider_items_slider` FOREIGN KEY (`slider_id`) REFERENCES `sliders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
