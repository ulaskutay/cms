-- Slider Layer Sistemi Veritabanı Şeması
-- Slider Revolution benzeri layer sistemi için
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Slider Layers Tablosu
CREATE TABLE IF NOT EXISTS `slider_layers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_item_id` int(11) NOT NULL,
  `type` enum('text','image','video','button','shape','html') DEFAULT 'text',
  `content` text DEFAULT NULL,
  `position_x` varchar(20) DEFAULT '50%',
  `position_y` varchar(20) DEFAULT '50%',
  `width` varchar(20) DEFAULT 'auto',
  `height` varchar(20) DEFAULT 'auto',
  `z_index` int(11) DEFAULT 1,
  `opacity` decimal(3,2) DEFAULT 1.00,
  `background_color` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `font_size` varchar(20) DEFAULT NULL,
  `font_weight` varchar(20) DEFAULT NULL,
  `text_align` enum('left','center','right','justify') DEFAULT 'left',
  `border_radius` varchar(20) DEFAULT '0',
  `box_shadow` varchar(100) DEFAULT NULL,
  `transform` text DEFAULT NULL COMMENT 'JSON: rotate, scale, skew',
  `animation_in` text DEFAULT NULL COMMENT 'JSON: type, duration, delay, direction',
  `animation_out` text DEFAULT NULL COMMENT 'JSON: type, duration, delay, direction',
  `hover_animation` text DEFAULT NULL COMMENT 'JSON: type, scale, etc.',
  `responsive` text DEFAULT NULL COMMENT 'JSON: mobile, tablet, desktop settings',
  `style` text DEFAULT NULL COMMENT 'JSON: custom CSS',
  `link_url` varchar(500) DEFAULT NULL,
  `link_target` enum('_self','_blank') DEFAULT '_self',
  `visibility` tinyint(1) DEFAULT 1,
  `order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `slider_item_id` (`slider_item_id`),
  KEY `order` (`order`),
  KEY `visibility` (`visibility`),
  CONSTRAINT `fk_slider_layers_item` FOREIGN KEY (`slider_item_id`) REFERENCES `slider_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Slider Backgrounds Tablosu (Gelişmiş arka plan efektleri için)
CREATE TABLE IF NOT EXISTS `slider_backgrounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_item_id` int(11) NOT NULL,
  `type` enum('image','video','gradient','color') DEFAULT 'image',
  `image_url` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `video_poster` varchar(500) DEFAULT NULL,
  `gradient_start` varchar(20) DEFAULT NULL,
  `gradient_end` varchar(20) DEFAULT NULL,
  `gradient_direction` enum('to right','to left','to bottom','to top','to bottom right','to bottom left','to top right','to top left') DEFAULT 'to right',
  `color` varchar(20) DEFAULT NULL,
  `parallax_enabled` tinyint(1) DEFAULT 0,
  `parallax_speed` decimal(3,2) DEFAULT 0.50 COMMENT '0.1 - 1.0',
  `ken_burns_enabled` tinyint(1) DEFAULT 0,
  `ken_burns_settings` text DEFAULT NULL COMMENT 'JSON: zoom, pan, duration',
  `overlay_enabled` tinyint(1) DEFAULT 0,
  `overlay_color` varchar(20) DEFAULT NULL,
  `overlay_opacity` decimal(3,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slider_item_id` (`slider_item_id`),
  CONSTRAINT `fk_slider_backgrounds_item` FOREIGN KEY (`slider_item_id`) REFERENCES `slider_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Slider Settings Tablosu (Gelişmiş slider ayarları)
ALTER TABLE `sliders` 
ADD COLUMN IF NOT EXISTS `parallax_enabled` tinyint(1) DEFAULT 0 AFTER `loop`,
ADD COLUMN IF NOT EXISTS `lazy_loading` tinyint(1) DEFAULT 1 AFTER `parallax_enabled`,
ADD COLUMN IF NOT EXISTS `responsive` tinyint(1) DEFAULT 1 AFTER `lazy_loading`,
ADD COLUMN IF NOT EXISTS `mobile_height` varchar(50) DEFAULT NULL AFTER `height`,
ADD COLUMN IF NOT EXISTS `tablet_height` varchar(50) DEFAULT NULL AFTER `mobile_height`,
ADD COLUMN IF NOT EXISTS `transition_duration` int(11) DEFAULT 600 AFTER `autoplay_delay`,
ADD COLUMN IF NOT EXISTS `easing` varchar(50) DEFAULT 'ease' AFTER `transition_duration`;

SET FOREIGN_KEY_CHECKS = 1;
