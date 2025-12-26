# Test Checklist - Tema Tabanlı Pages Modülü

## Pre-Implementation Checklist ✓

- [x] ThemeLoader güncellemeleri
- [x] ModuleLoader güncellemeleri
- [x] ThemeManager güncellemeleri
- [x] Pages modülü oluşturuldu
- [x] Module.json hazırlandı
- [x] Controller yazıldı
- [x] Model yazıldı
- [x] View'lar kopyalandı
- [x] Router güncellendi
- [x] Sidebar güncellendi
- [x] Core dosyalar yedeklendi
- [x] Core dosyalar kaldırıldı
- [x] Migration script hazırlandı
- [x] Dokümantasyon tamamlandı

## Post-Implementation Testing

### 1. Veritabanı Kontrolü
```bash
# Migration script'i çalıştır
php install/migrate_pages_module.php
```

**Kontrol Edilecekler:**
- [ ] Script hatasız çalışıyor
- [ ] Backup oluşturuldu
- [ ] `modules` tablosuna kayıt eklendi
- [ ] Sayfa sayısı doğru rapor edildi
- [ ] Meta kayıt sayısı doğru rapor edildi

**SQL Kontrolü:**
```sql
-- Modül kaydını kontrol et
SELECT * FROM modules WHERE slug = 'pages';

-- Sonuç:
-- name: pages
-- path: themes/starter/modules/pages
-- is_active: 1
```

### 2. Admin Panel Erişimi

#### 2.1 Modül Menüsü
- [ ] Admin paneli açılıyor
- [ ] Sidebar'da "Modüller" bölümü var
- [ ] "Sayfalar" menüsü görünüyor
- [ ] İkon doğru (description)
- [ ] Menü pozisyonu doğru (30)

#### 2.2 Sayfa Listesi
URL: `/admin?page=module/pages`

- [ ] Sayfa yükleniyor (404 yok)
- [ ] Tüm sayfalar listeleniyor
- [ ] Durum filtreleri çalışıyor (Tümü, Yayında, Taslak, Çöp)
- [ ] İstatistikler doğru
- [ ] "Yeni Sayfa" butonu var ve çalışıyor

#### 2.3 Yeni Sayfa Oluşturma
URL: `/admin?page=module/pages/create`

- [ ] Form açılıyor
- [ ] Başlık alanı var
- [ ] Slug alanı var (otomatik oluşuyor)
- [ ] İçerik editörü çalışıyor
- [ ] Excerpt alanı var
- [ ] Durum seçimi var (Draft/Published)
- [ ] Görünürlük seçimi var
- [ ] Meta alanları var (SEO)
- [ ] Özel alanlar görünüyor
- [ ] Kaydet butonu çalışıyor

**Test:**
```
1. "Test Sayfası" başlığıyla yeni sayfa oluştur
2. İçerik ekle
3. "Taslak" olarak kaydet
4. Başarı mesajı görünmeli
5. Edit sayfasına yönlendirilmeli
```

#### 2.4 Sayfa Düzenleme
URL: `/admin?page=module/pages/edit/1`

- [ ] Sayfa bilgileri yükleniyor
- [ ] Tüm alanlar dolu
- [ ] Özel alanlar görünüyor ve dolu
- [ ] Versiyon geçmişi sekmesi var
- [ ] Güncelle butonu çalışıyor
- [ ] Sil butonu çalışıyor

**Test:**
```
1. Bir sayfayı aç
2. Başlığı değiştir
3. Kaydet
4. Başarı mesajı görünmeli
5. Değişiklik kaydedilmiş olmalı
```

#### 2.5 Özel Alan Türleri
- [ ] Text alanları çalışıyor
- [ ] Textarea alanları çalışıyor
- [ ] Image picker çalışıyor
- [ ] Repeater alanları çalışıyor
  - [ ] Yeni item eklenebiliyor
  - [ ] Item silinebiliyor
  - [ ] Sıralama değiştirilebiliyor
- [ ] Checkbox alanları çalışıyor

#### 2.6 Sayfa İşlemleri
- [ ] Durum değiştirme (Published ↔ Draft)
- [ ] Çöpe taşıma
- [ ] Çöpten geri yükleme
- [ ] Kalıcı silme
- [ ] Sayfa kopyalama (Duplicate)

#### 2.7 Versiyon Kontrolü
URL: `/admin?page=module/pages/versions/1`

- [ ] Versiyon listesi görünüyor
- [ ] Her versiyon için tarih/saat var
- [ ] Kullanıcı bilgisi görünüyor
- [ ] Versiyon numaraları doğru
- [ ] Versiyon görüntüleme çalışıyor
- [ ] Eski versiyona geri dönme çalışıyor

### 3. Frontend Kontrolü

#### 3.1 Sayfa Görüntüleme
- [ ] Sayfalar frontend'te görünüyor
- [ ] Slug-based routing çalışıyor (örn: `/hakkimizda`)
- [ ] Sayfa içeriği doğru render ediliyor
- [ ] SEO meta tagları doğru

#### 3.2 Özel Alanlar Frontend
- [ ] Hero görsel gösteriliyor
- [ ] Özel alanlar erişilebilir
- [ ] Repeater veriler doğru işleniyor

### 4. Tema Değiştirme Testi

#### 4.1 Starter Tema → Başka Tema (Pages modülü yok)
```
1. Başka bir temayı aktif et
2. Admin paneline git
3. "Sayfalar" menüsü kaybolmalı
4. /admin?page=module/pages → 404 veya hata
5. Veritabanındaki sayfalar korunmalı
```

- [ ] Eski tema modülü deaktive edildi
- [ ] Sidebar menüsü kayboldu
- [ ] Sayfa verileri korundu

#### 4.2 Geri Dönüş
```
1. Starter temayı tekrar aktif et
2. "Sayfalar" menüsü geri gelmeli
3. Tüm sayfalar erişilebilir olmalı
```

- [ ] Modül tekrar yüklendi
- [ ] Menü görünüyor
- [ ] Tüm sayfalar erişilebilir
- [ ] Veri kaybı yok

### 5. Performans ve Güvenlik

#### 5.1 Performans
- [ ] Sayfa listesi hızlı yükleniyor (< 1 saniye)
- [ ] Sayfa düzenleme hızlı açılıyor
- [ ] Büyük içerikler sorunsuz kaydediliyor
- [ ] Repeater alanları performans sounu yaratmıyor

#### 5.2 Güvenlik
- [ ] Yetkisiz kullanıcı erişemiyor
- [ ] XSS koruması var (içerik escape ediliyor)
- [ ] CSRF koruması var
- [ ] SQL injection korumalı

#### 5.3 Yetki Kontrolü
```sql
-- Test kullanıcısı oluştur (yetkisiz)
INSERT INTO users (username, email, password, role_id) 
VALUES ('test_user', 'test@test.com', 'hashed_pwd', 3);
```

- [ ] Yetkisiz kullanıcı sayfa listesini göremiyor
- [ ] Yetkisiz kullanıcı sayfa oluşturamıyor
- [ ] Yetkisiz kullanıcı sayfa düzenleyemiyor

### 6. Hata Durumları

#### 6.1 Eksik Veriler
- [ ] Başlık boşken kaydetme engelleniyor
- [ ] Hata mesajı gösteriliyor
- [ ] Form verileri korunuyor (kaybolmuyor)

#### 6.2 Geçersiz ID'ler
- [ ] Olmayan sayfa ID'si → hata mesajı
- [ ] Geçersiz versiyon ID'si → hata mesajı
- [ ] Kullanıcı listeye yönlendiriliyor

#### 6.3 Veritabanı Hataları
- [ ] Bağlantı hatası gracefully handle ediliyor
- [ ] Kullanıcıya anlaşılır mesaj gösteriliyor

### 7. Rollback Testi (Opsiyonel)

```bash
# Rollback yap
cp -r storage/backups/core_pages_backup_*/app/* app/

# Veritabanını temizle
mysql -u root -p cms -e "DELETE FROM modules WHERE slug='pages' AND path LIKE 'themes/%';"
```

**Kontrol:**
- [ ] Eski dosyalar geri yüklendi
- [ ] Eski `/admin?page=pages` URL'i çalışıyor
- [ ] Tüm sayfalar erişilebilir
- [ ] Veri kaybı yok

## Final Checklist

### Başarı Kriterleri
- [ ] Tüm core sistem testleri geçti
- [ ] Tüm admin panel testleri geçti
- [ ] Frontend testleri geçti
- [ ] Tema değiştirme testleri geçti
- [ ] Performans kabul edilebilir
- [ ] Güvenlik kontrolleri geçti
- [ ] Hata durumları düzgün handle ediliyor

### Dokümantasyon
- [x] QUICK_START.md hazır
- [x] MIGRATION_PAGES.md hazır
- [x] ARCHITECTURE.md hazır
- [x] IMPLEMENTATION_SUMMARY.md hazır
- [x] Bu checklist hazır

### Yedekler
- [x] Core dosyalar yedeklendi
- [x] Yedek konumu kayıtlı
- [x] Rollback prosedürü dokümante edildi

## Sonuç

**Status:** ✅ Implementation Complete - Ready for Testing

**Next Steps:**
1. Run migration script
2. Test all features systematically
3. Deploy to production (if tests pass)
4. Monitor for issues

**Rollback Available:** Yes
**Data Safety:** 100% (No data modification)
**Risk Level:** Low (Backup available, data intact)

