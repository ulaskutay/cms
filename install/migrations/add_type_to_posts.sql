-- Posts tablosuna type kolonu ekleme migration
-- Sayfalar sistemi için gerekli
-- Bu dosyayı veritabanınızda çalıştırın

SET NAMES utf8mb4;

-- type kolonu ekle (eğer zaten varsa hata verebilir, o zaman güvenle görmezden gelebilirsiniz)
ALTER TABLE `posts` 
ADD COLUMN `type` enum('post','page') DEFAULT 'post' AFTER `status`;

-- Mevcut kayıtların type'ını 'post' olarak ayarla
UPDATE `posts` SET `type` = 'post' WHERE `type` IS NULL OR `type` = '';

-- Index ekle (eğer zaten varsa hata verebilir)
ALTER TABLE `posts` ADD INDEX `type` (`type`);
