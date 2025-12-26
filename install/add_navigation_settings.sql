-- Navigation Buton Ayarları için Kolonlar Ekleme
-- Slider tablosuna navigation butonları için renk, boyut ve pozisyon ayarları ekler
-- Not: Eğer kolonlar zaten varsa hata vermez

-- nav_button_color
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_color');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_color` varchar(7) DEFAULT ''#137fec'' AFTER `navigation`', 
    'SELECT ''Column nav_button_color already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_bg_color
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_bg_color');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_bg_color` varchar(7) DEFAULT ''#ffffff'' AFTER `nav_button_color`', 
    'SELECT ''Column nav_button_bg_color already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_bg_opacity
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_bg_opacity');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_bg_opacity` decimal(3,2) DEFAULT 0.90 AFTER `nav_button_bg_color`', 
    'SELECT ''Column nav_button_bg_opacity already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_hover_color
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_hover_color');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_hover_color` varchar(7) DEFAULT ''#137fec'' AFTER `nav_button_bg_opacity`', 
    'SELECT ''Column nav_button_hover_color already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_hover_bg_color
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_hover_bg_color');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_hover_bg_color` varchar(7) DEFAULT ''#ffffff'' AFTER `nav_button_hover_color`', 
    'SELECT ''Column nav_button_hover_bg_color already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_hover_bg_opacity
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_hover_bg_opacity');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_hover_bg_opacity` decimal(3,2) DEFAULT 0.90 AFTER `nav_button_hover_bg_color`', 
    'SELECT ''Column nav_button_hover_bg_opacity already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_size
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_size');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_size` varchar(10) DEFAULT ''50px'' AFTER `nav_button_hover_bg_opacity`', 
    'SELECT ''Column nav_button_size already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_icon_size
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_icon_size');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_icon_size` varchar(10) DEFAULT ''32px'' AFTER `nav_button_size`', 
    'SELECT ''Column nav_button_icon_size already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_position
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_position');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_position` enum(''inside'',''outside'') DEFAULT ''inside'' AFTER `nav_button_icon_size`', 
    'SELECT ''Column nav_button_position already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_opacity
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_opacity');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_opacity` decimal(3,2) DEFAULT 0.90 AFTER `nav_button_position`', 
    'SELECT ''Column nav_button_opacity already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- nav_button_border_radius
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'sliders' 
    AND COLUMN_NAME = 'nav_button_border_radius');
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `sliders` ADD COLUMN `nav_button_border_radius` varchar(10) DEFAULT ''50%'' AFTER `nav_button_opacity`', 
    'SELECT ''Column nav_button_border_radius already exists''');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Mevcut kayıtlar için varsayılan değerleri güncelle
UPDATE `sliders` SET 
    `nav_button_color` = COALESCE(`nav_button_color`, '#137fec'),
    `nav_button_bg_color` = COALESCE(`nav_button_bg_color`, '#ffffff'),
    `nav_button_bg_opacity` = COALESCE(`nav_button_bg_opacity`, 0.90),
    `nav_button_hover_color` = COALESCE(`nav_button_hover_color`, '#137fec'),
    `nav_button_hover_bg_color` = COALESCE(`nav_button_hover_bg_color`, '#ffffff'),
    `nav_button_hover_bg_opacity` = COALESCE(`nav_button_hover_bg_opacity`, 0.90),
    `nav_button_size` = COALESCE(`nav_button_size`, '50px'),
    `nav_button_icon_size` = COALESCE(`nav_button_icon_size`, '32px'),
    `nav_button_position` = COALESCE(`nav_button_position`, 'inside'),
    `nav_button_opacity` = COALESCE(`nav_button_opacity`, 0.90),
    `nav_button_border_radius` = COALESCE(`nav_button_border_radius`, '50%')
WHERE `nav_button_color` IS NULL OR `nav_button_color` = '';
