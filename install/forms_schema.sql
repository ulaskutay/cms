-- Form Sistemi Veritabanı Şeması
-- MySQL 5.7+ ve MariaDB 10.2+ uyumludur

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Forms Tablosu
CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `success_message` text DEFAULT NULL,
  `redirect_url` varchar(500) DEFAULT NULL,
  `email_notification` tinyint(1) DEFAULT 1,
  `notification_email` varchar(255) DEFAULT NULL,
  `email_subject` varchar(255) DEFAULT NULL,
  `submit_button_text` varchar(100) DEFAULT 'Gönder',
  `submit_button_color` varchar(20) DEFAULT '#137fec',
  `form_style` enum('default','modern','minimal','bordered') DEFAULT 'default',
  `layout` enum('vertical','horizontal','inline') DEFAULT 'vertical',
  `status` enum('active','inactive') DEFAULT 'active',
  `submission_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Form Fields Tablosu
CREATE TABLE IF NOT EXISTS `form_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `type` enum('text','email','phone','number','textarea','select','checkbox','radio','file','date','time','datetime','hidden','heading','paragraph','divider') DEFAULT 'text',
  `label` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `default_value` text DEFAULT NULL,
  `options` text DEFAULT NULL COMMENT 'JSON: select, checkbox, radio için seçenekler',
  `validation` text DEFAULT NULL COMMENT 'JSON: required, min, max, pattern vb.',
  `required` tinyint(1) DEFAULT 0,
  `css_class` varchar(255) DEFAULT NULL,
  `width` enum('full','half','third','quarter') DEFAULT 'full',
  `help_text` varchar(500) DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  CONSTRAINT `fk_form_fields_form` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Form Submissions Tablosu
CREATE TABLE IF NOT EXISTS `form_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `data` text NOT NULL COMMENT 'JSON: Gönderilen form verileri',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `referrer` varchar(500) DEFAULT NULL,
  `status` enum('new','read','spam','archived') DEFAULT 'new',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_form_submissions_form` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

