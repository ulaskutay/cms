# Sistem Mimarisi - Tema Tabanlı Modüller

## Yeni Mimari

```
┌─────────────────────────────────────────────────────────┐
│                     CMS Core                             │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────┐      ┌──────────────┐                │
│  │ ThemeLoader  │─────▶│ ThemeManager │                │
│  └──────┬───────┘      └──────────────┘                │
│         │                                                │
│         │ loadThemeModules()                            │
│         ▼                                                │
│  ┌──────────────┐                                       │
│  │ModuleLoader  │                                       │
│  │              │                                       │
│  │ - scanThemeModules()                                │
│  │ - loadThemeModules()                                │
│  │ - unloadThemeModules()                              │
│  └──────────────┘                                       │
│                                                          │
└─────────────────────────────────────────────────────────┘
                        │
                        │ Modülleri Yükle
                        ▼
┌─────────────────────────────────────────────────────────┐
│              Themes / starter / modules                  │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────────────────────────────────────┐          │
│  │          pages/ (Theme Module)           │          │
│  ├──────────────────────────────────────────┤          │
│  │                                           │          │
│  │  • module.json (Manifest)                │          │
│  │  • Controller.php                        │          │
│  │  • models/PageModel.php                  │          │
│  │  • views/admin/*.php                     │          │
│  │                                           │          │
│  └──────────────────────────────────────────┘          │
│                                                          │
└─────────────────────────────────────────────────────────┘
                        │
                        │ Veri Erişimi
                        ▼
┌─────────────────────────────────────────────────────────┐
│                    Database                              │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  • posts (type='page')     ← Değişmedi                  │
│  • page_meta               ← Değişmedi                  │
│  • modules                 ← Pages kaydı eklendi        │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

## Veri Akışı

### Sayfa Listesi Görüntüleme
```
User Request: /admin?page=module/pages
    │
    ▼
public/admin.php (Router)
    │
    ├─▶ ModuleLoader::handleAdminRoute('module/pages')
    │
    ▼
themes/starter/modules/pages/Controller.php
    │
    ├─▶ PagesModuleController::admin_index()
    │
    ▼
themes/starter/modules/pages/models/PageModel.php
    │
    ├─▶ PageModel::getAll()
    │
    ▼
Database: SELECT * FROM posts WHERE type='page'
    │
    ▼
themes/starter/modules/pages/views/admin/index.php
    │
    ▼
HTML Response → User
```

### Tema Değiştirme
```
User: Activate New Theme
    │
    ▼
ThemeManager::activateTheme('new-theme')
    │
    ├─▶ 1. unloadOldThemeModules()
    │   │
    │   └─▶ ModuleLoader::unloadThemeModules(old-theme-path)
    │       │
    │       └─▶ Deactivate Pages module from old theme
    │
    ├─▶ 2. Update database: themes.is_active
    │
    └─▶ 3. loadNewThemeModules()
        │
        └─▶ ModuleLoader::loadThemeModules(new-theme-path)
            │
            └─▶ Activate Pages module from new theme

Result: New theme's Pages module is now active
```

## Modül Kayıt Sistemi

```
Theme Activation
    │
    ▼
ThemeLoader::loadActiveTheme()
    │
    └─▶ loadThemeModules()
        │
        ▼
ModuleLoader::loadThemeModules(themePath)
    │
    ├─▶ scanThemeModules(themePath)
    │   │
    │   └─▶ Read themes/starter/modules/pages/module.json
    │       │
    │       └─▶ Return module manifest
    │
    ├─▶ Register in all_modules array
    │   │
    │   └─▶ Add 'is_theme_module' = true
    │
    └─▶ activateModule('pages')
        │
        ├─▶ Load Controller.php
        │
        ├─▶ Load models/*.php
        │
        ├─▶ Register routes
        │
        └─▶ Register admin menu
            │
            └─▶ Sidebar automatically shows "Sayfalar"
```

## Dosya Konumları

### Eski Sistem (Kaldırıldı)
```
❌ app/controllers/PageController.php
❌ app/models/Page.php
❌ app/views/admin/pages/
```

### Yeni Sistem (Aktif)
```
✓ themes/starter/modules/pages/Controller.php
✓ themes/starter/modules/pages/models/PageModel.php
✓ themes/starter/modules/pages/views/admin/
✓ themes/starter/modules/pages/module.json
```

### Yedekler
```
📦 storage/backups/core_pages_backup_20251227_002320/
   ├── app/controllers/PageController.php
   ├── app/models/Page.php
   └── app/views/admin/pages/
```

## Avantajlar

1. **Tema Özgürlüğü**: Her tema kendi sayfa yapısını getirebilir
2. **Modülerlik**: Sayfalar artık tema ile birlikte gelir
3. **Esneklik**: Farklı temalar farklı sayfa özellikleri sunabilir
4. **Veri Güvenliği**: Veritabanı değişmez, sadece erişim yöntemi değişir
5. **Geri Uyumluluk**: Eski veriler aynen çalışır

## Örnek: Farklı Temalar

```
E-ticaret Teması:
themes/ecommerce/modules/pages/
    └── Ürün sayfaları, kategori sayfaları

Blog Teması:
themes/blog/modules/pages/
    └── Makale sayfaları, yazar sayfaları

Portfolio Teması:
themes/portfolio/modules/pages/
    └── Proje sayfaları, galeri sayfaları

Starter Teması:
themes/starter/modules/pages/
    └── Genel amaçlı sayfalar (mevcut)
```

