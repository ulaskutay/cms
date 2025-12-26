-- Tema Sistemi Veritabanı Şeması
-- CMS Tema Yönetimi için gerekli tablolar

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Yüklü temalar tablosu
CREATE TABLE IF NOT EXISTS `themes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `slug` VARCHAR(100) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `version` VARCHAR(20) DEFAULT '1.0.0',
    `author` VARCHAR(255) DEFAULT NULL,
    `author_url` VARCHAR(500) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `screenshot` VARCHAR(500) DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `settings_schema` JSON DEFAULT NULL COMMENT 'theme.json içeriği',
    `installed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tema ayarları tablosu (kullanıcı özelleştirmeleri)
CREATE TABLE IF NOT EXISTS `theme_options` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `theme_id` INT(11) NOT NULL,
    `option_group` VARCHAR(50) DEFAULT 'general' COMMENT 'colors, fonts, custom vb.',
    `option_key` VARCHAR(100) NOT NULL,
    `option_value` LONGTEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `theme_option` (`theme_id`, `option_key`),
    KEY `theme_id` (`theme_id`),
    KEY `option_group` (`option_group`),
    CONSTRAINT `fk_theme_options_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sayfa section'ları tablosu (ana sayfa bölümleri vb.)
CREATE TABLE IF NOT EXISTS `page_sections` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `theme_id` INT(11) DEFAULT NULL,
    `page_type` VARCHAR(50) NOT NULL COMMENT 'home, about, contact vb.',
    `section_id` VARCHAR(100) NOT NULL COMMENT 'hero, features, testimonials vb.',
    `section_component` VARCHAR(100) DEFAULT NULL COMMENT 'Kullanılacak component adı',
    `title` VARCHAR(255) DEFAULT NULL,
    `subtitle` VARCHAR(500) DEFAULT NULL,
    `content` LONGTEXT DEFAULT NULL,
    `settings` JSON DEFAULT NULL COMMENT 'Section özel ayarları (renkler, butonlar vb.)',
    `items` JSON DEFAULT NULL COMMENT 'Section içindeki öğeler (features listesi vb.)',
    `sort_order` INT(11) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `theme_id` (`theme_id`),
    KEY `page_type` (`page_type`),
    KEY `section_id` (`section_id`),
    KEY `sort_order` (`sort_order`),
    CONSTRAINT `fk_page_sections_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Özel CSS/JS tablosu (tema bazlı özel kodlar)
CREATE TABLE IF NOT EXISTS `theme_custom_code` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `theme_id` INT(11) NOT NULL,
    `code_type` ENUM('css', 'js', 'head', 'footer') NOT NULL DEFAULT 'css',
    `code_content` LONGTEXT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `theme_code_type` (`theme_id`, `code_type`),
    CONSTRAINT `fk_theme_custom_code_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

