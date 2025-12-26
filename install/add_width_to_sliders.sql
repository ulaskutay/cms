-- Slider tablosuna width (genişlik) kolonu ekle
-- Bu dosyayı phpMyAdmin veya MySQL komut satırından çalıştırabilirsiniz

-- Kolonu ekle (eğer zaten varsa hata verebilir, o zaman ikinci sorguyu çalıştırmayın)
ALTER TABLE `sliders` ADD COLUMN `width` varchar(50) DEFAULT '100%' AFTER `height`;

-- Varsayılan değerleri güncelle (mevcut kayıtlar için)
UPDATE `sliders` SET `width` = '100%' WHERE `width` IS NULL OR `width` = '';

