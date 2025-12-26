-- Post Versions Schema
-- Yazı versiyon geçmişi tablosu

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `post_versions` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `post_id` bigint(20) NOT NULL,
    `version_number` INT(11) NOT NULL DEFAULT 1,
    `title` VARCHAR(500) NOT NULL,
    `slug` VARCHAR(500) NOT NULL,
    `excerpt` TEXT,
    `content` LONGTEXT,
    `featured_image` VARCHAR(500),
    `meta_title` VARCHAR(255),
    `meta_description` TEXT,
    `meta_keywords` VARCHAR(500),
    `created_by` int(11) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX `idx_post_id` (`post_id`),
    INDEX `idx_version_number` (`version_number`),
    INDEX `idx_created_at` (`created_at`),
    
    CONSTRAINT `fk_post_versions_post` FOREIGN KEY (`post_id`) 
        REFERENCES `posts`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_post_versions_user` FOREIGN KEY (`created_by`) 
        REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Posts tablosuna version kolonu ekle (eğer yoksa)
ALTER TABLE `posts` ADD COLUMN `version` INT(11) NOT NULL DEFAULT 1 AFTER `views`;

SET FOREIGN_KEY_CHECKS = 1;
