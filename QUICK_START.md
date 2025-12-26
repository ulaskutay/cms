# Hızlı Başlangıç - Tema Tabanlı Pages Modülü

## Kullanıma Hazır!

Pages modülü artık Starter temasında bir modül olarak çalışıyor. İşte hızlı başlangıç:

## Adım 1: Veritabanı Güncellemesi

Migration script'ini çalıştırın:

```bash
php install/migrate_pages_module.php
```

Bu script:
- Mevcut dosyaları yedekler
- Pages modülünü veritabanına kaydeder
- Veri uyumluluğunu test eder

## Adım 2: Test Edin

Admin paneline gidin:
```
http://your-site.com/admin?page=module/pages
```

Şunları kontrol edin:
- ✓ Sayfa listesi görünüyor
- ✓ Yeni sayfa oluşturabiliyorsunuz
- ✓ Sayfa düzenleyebiliyorsunuz
- ✓ Sidebar'da "Sayfalar" menüsü görünüyor

## Adım 3: Her Şey Çalışıyorsa

Cleanup yapın (opsiyonel):
```bash
php install/migrate_pages_module.php --cleanup
```

Bu komut eski core dosyaları siler (yedekleri kalır).

## Önemli Notlar

### URL Değişiklikleri
- **Eski**: `/admin?page=pages`
- **Yeni**: `/admin?page=module/pages`

### Yedekler
Tüm yedekler güvende:
```
storage/backups/core_pages_backup_20251227_002320/
```

### Veri Güvenliği
- Tüm sayfa verileri korundu
- Veritabanı değişmedi
- Sadece erişim yöntemi değişti

## Sorun Giderme

### Pages Modülü Görünmüyor
1. Tarayıcı cache'ini temizleyin
2. PHP cache'ini temizleyin (opcache vb.)
3. Modülün aktif olduğunu kontrol edin:
   ```sql
   SELECT * FROM modules WHERE slug = 'pages';
   ```

### 404 Hatası
URL'nin doğru olduğundan emin olun: `module/pages` (pages değil!)

### Rollback Gerekirse
```bash
# Yedekleri geri yükle
cp -r storage/backups/core_pages_backup_20251227_002320/app/* app/

# Modülü sil
mysql -u USER -p DATABASE -e "DELETE FROM modules WHERE slug='pages' AND path LIKE 'themes/%';"
```

## Başarılı! 🎉

Artık tema tabanlı Pages modülü kullanıma hazır. Her yeni tema kendi sayfa yapısıyla gelebilir!

Daha fazla bilgi için: `install/MIGRATION_PAGES.md`

