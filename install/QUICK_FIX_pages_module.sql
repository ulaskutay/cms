-- Pages modülünü tema modülü olarak kaydet
-- Eğer zaten varsa güncelle

INSERT INTO modules (name, slug, label, description, icon, version, author, path, is_active, installed_at, created_at)
VALUES (
    'pages',
    'pages',
    'Sayfalar',
    'Statik sayfa yönetimi - Starter tema için özelleştirilmiş',
    'description',
    '1.0.0',
    'CMS',
    'themes/starter/modules/pages',
    1,
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    path = 'themes/starter/modules/pages',
    is_active = 1,
    updated_at = NOW();

