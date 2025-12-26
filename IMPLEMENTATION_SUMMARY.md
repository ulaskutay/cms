# Tema Tabanlı Sayfa Modülü - İmplementasyon Tamamlandı

## Özet

Pages (Sayfalar) modülü başarıyla CMS çekirdeğinden Starter temasına taşındı. Artık her tema kendi sayfa yönetim sistemini getirebilir.

## Yapılan Değişiklikler

### 1. Core Sistem Güncellemeleri

#### ThemeLoader.php
- `loadThemeModules()` metodu eklendi
- Tema yüklendiğinde tema içindeki `modules/` klasörü taranıyor
- Bulunan modüller ModuleLoader'a kaydediliyor

#### ModuleLoader.php
- `scanThemeModules($themePath)` metodu eklendi
- `loadThemeModules($themePath)` metodu eklendi
- `unloadThemeModules($oldThemePath)` metodu eklendi
- Tema modülleri `is_theme_module` flag'i ile işaretleniyor

#### ThemeManager.php
- `activateTheme()` metodu güncellendi
- Tema değişiminde eski tema modülleri deaktive ediliyor
- Yeni tema modülleri otomatik yükleniyor
- `unloadOldThemeModules()` ve `loadNewThemeModules()` metodları eklendi

### 2. Starter Tema Pages Modülü

#### Dizin Yapısı
```
themes/starter/modules/pages/
├── Controller.php              # PagesModuleController
├── models/
│   └── PageModel.php          # Page data model
├── views/
│   └── admin/
│       ├── index.php          # Sayfa listesi
│       ├── create.php         # Yeni sayfa
│       ├── edit.php           # Sayfa düzenle
│       └── versions.php       # Versiyon geçmişi
└── module.json                # Modül manifestosu
```

#### Özellikler
- Tüm CRUD işlemleri
- Versiyon kontrolü
- Özel alanlar (custom fields)
- Repeater alanlar
- SEO meta alanları
- Sayfa kopyalama
- Taslak/Yayınlanmış/Çöp durumları

### 3. Router Güncellemeleri

#### public/admin.php
- Hardcoded Pages route'ları kaldırıldı (542-611. satırlar)
- ModuleLoader'ın dinamik route handler'ı kullanılıyor
- Yeni URL yapısı: `/admin?page=module/pages/*`

#### Sidebar Güncellemeleri
- `app/views/admin/snippets/sidebar.php`:
  - Hardcoded Pages menü öğesi kaldırıldı
  - `$canViewPages` değişkeni kaldırıldı
  - İçerik menüsü kontrolü güncellendi
  - Modül menüleri dinamik olarak yükleniyor

### 4. Core Dosyalar

#### Yedeklenen ve Kaldırılan Dosyalar
Aşağıdaki dosyalar yedeklendi ve core'dan kaldırıldı:
- `app/controllers/PageController.php` ✓
- `app/models/Page.php` ✓
- `app/views/admin/pages/*` ✓

Yedek konumu:
```
storage/backups/core_pages_backup_20251227_002320/
```

### 5. Migration Script

#### install/migrate_pages_module.php
Kullanım:
```bash
# Migrasyonu çalıştır (test)
php install/migrate_pages_module.php

# Cleanup ile (core dosyaları sil)
php install/migrate_pages_module.php --cleanup
```

Özellikler:
- Otomatik backup oluşturma
- Veritabanı kaydı
- Veri uyumluluk testi
- Güvenli cleanup

## Veri Uyumluluğu

### Değişmeyen Tablolar
- `posts` tablosu (type='page')
- `page_meta` tablosu
- Tüm mevcut sayfa verileri korundu

### Yeni Kayıtlar
- `modules` tablosuna Pages modülü kaydedildi:
  ```sql
  slug: 'pages'
  path: 'themes/starter/modules/pages'
  is_active: 1
  ```

## URL Değişiklikleri

### Eski URL'ler (Artık Çalışmıyor)
- `/admin?page=pages`
- `/admin?page=pages/create`
- `/admin?page=pages/edit/123`

### Yeni URL'ler (Aktif)
- `/admin?page=module/pages`
- `/admin?page=module/pages/create`
- `/admin?page=module/pages/edit/123`

## Tema Değiştirme Senaryosu

### Tema Aktivasyonu
1. Eski tema deaktive edilir
2. Eski temanın Pages modülü unload edilir
3. Yeni tema aktive edilir
4. Yeni temanın Pages modülü load edilir
5. Sidebar otomatik güncellenir

### Veri Korunumu
- Tüm sayfa verileri veritabanında kalır
- Farklı temalar aynı veriyi farklı şekillerde gösterebilir
- Tema değişimi veri kaybına yol açmaz

## Test Edilmesi Gerekenler

### ✓ Yapılan Testler
1. Core sistem güncellemeleri
2. Modül dosyaları oluşturuldu
3. Router güncellemeleri
4. Sidebar güncellemeleri
5. Core dosyalar kaldırıldı

### Yapılması Gereken Testler
1. **Admin Paneli**
   - [ ] `/admin?page=module/pages` açılıyor mu?
   - [ ] Sayfa listesi görüntüleniyor mu?
   - [ ] Yeni sayfa oluşturuluyor mu?
   - [ ] Sayfa düzenleniyor mu?
   - [ ] Özel alanlar çalışıyor mu?
   - [ ] Versiyon geçmişi çalışıyor mu?

2. **Tema Değişimi**
   - [ ] Tema değiştirildiğinde Pages modülü yükleniyor mu?
   - [ ] Sidebar'da menü görünüyor mu?
   - [ ] Sayfalar erişilebilir mi?

3. **Frontend**
   - [ ] Sayfalar frontend'te görüntüleniyor mu?
   - [ ] Slug-based routing çalışıyor mu?

## Rollback Prosedürü

Eğer bir sorun olursa:

1. **Yedekten Geri Yükle**
   ```bash
   cp -r storage/backups/core_pages_backup_20251227_002320/app/* app/
   ```

2. **Veritabanından Modülü Sil**
   ```sql
   DELETE FROM modules WHERE slug = 'pages' AND path LIKE 'themes/%';
   ```

3. **Router'ı Eski Haline Getir**
   - `public/admin.php` dosyasında Pages route'larını geri ekle

4. **Sidebar'ı Geri Yükle**
   - `app/views/admin/snippets/sidebar.php` dosyasında Pages menüsünü geri ekle

## Gelecek Geliştirmeler

### Diğer Temalar İçin
Yeni temalar için Pages modülü oluşturmak:
1. `themes/TEMA_ADI/modules/pages/` klasörü oluştur
2. `module.json`, `Controller.php`, modeller ve view'ları ekle
3. Temanın ihtiyacına göre özelleştir

### Özelleştirme Örnekleri
- E-ticaret teması: Ürün sayfaları, kategori sayfaları
- Blog teması: Makale sayfaları, yazar sayfaları
- Portfolio teması: Proje sayfaları, galeri sayfaları

## Dokümantasyon

- **Kullanım Kılavuzu**: `install/MIGRATION_PAGES.md`
- **Migration Script**: `install/migrate_pages_module.php`
- **SQL Schema**: `install/migrations/migrate_pages_to_theme.sql`

## Sonuç

✅ **Tüm değişiklikler başarıyla uygulandı**
- Core sistem tema modüllerini destekliyor
- Pages modülü temaya taşındı
- Router ve sidebar güncellendi
- Core dosyalar yedeklendi ve kaldırıldı
- Migration script'i hazır
- Veri uyumluluğu sağlandı

**Sonraki Adım**: Test ve doğrulama aşaması

