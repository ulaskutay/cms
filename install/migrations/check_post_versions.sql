-- Check and create post_versions table if not exists
-- Sayfa versiyon sistemi için gerekli tablo

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
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add version column to posts table if not exists
SET @dbname = DATABASE();
SET @tablename = 'posts';
SET @columnname = 'version';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " INT(11) NOT NULL DEFAULT 1 AFTER views")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET FOREIGN_KEY_CHECKS = 1;

