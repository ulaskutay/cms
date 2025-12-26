-- Migration: Move Pages to Theme Module
-- No actual SQL changes needed - pages data stays in the same tables
-- This is just to document the structure

-- The 'posts' table with type='page' remains unchanged
-- The 'page_meta' table remains unchanged
-- Data is compatible with both old and new system

-- Register the theme module in the modules table
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

