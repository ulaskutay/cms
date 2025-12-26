# 🚀 CMS Kurulum Rehberi

## Basit Kurulum

WordPress tarzı otomatik kurulum sistemi ile kurulum çok kolay!

### Adımlar:

1. **Tarayıcıda açın:** `https://siteniz.com/install.php`

2. **Adım 1:** Veritabanı bilgilerinizi girin
   - Veritabanı sunucusu (genellikle `localhost`)
   - Veritabanı adı
   - Veritabanı kullanıcı adı
   - Veritabanı şifresi

3. **Adım 2:** Site ve admin bilgilerinizi girin
   - Site adı
   - Yönetici kullanıcı adı
   - Yönetici e-posta
   - Yönetici şifresi

4. **Sistem otomatik olarak:**
   - ✅ Config dosyasını oluşturur
   - ✅ Tüm veritabanı tablolarını oluşturur
   - ✅ Admin kullanıcısını oluşturur
   - ✅ Varsayılan ayarları ekler

5. **Kurulum tamamlandı!** Admin paneline giriş yapabilirsiniz.

## Dosya Yapısı

```
install/
├── install.php              # Ana kurulum giriş sayfası (root'ta)
├── step1.php                # Adım 1: Veritabanı bilgileri
├── step2.php                # Adım 2: Site ve admin bilgileri
├── install_process.php      # Kurulum işlem sayfası
├── install_process_action.php  # Kurulum işlemleri (config, tablolar, admin)
├── step3.php                # Kurulum tamamlandı sayfası
├── schema.sql               # Ana veritabanı şeması
├── sliders_schema.sql       # Slider tabloları
└── slider_layers_schema.sql # Slider layer tabloları
```

## Sorun Giderme

**"Veritabanı bağlantı hatası" alıyorsanız:**
- cPanel'de veritabanı oluşturduğunuzdan emin olun
- Kullanıcı adı ve şifresinin doğru olduğundan emin olun

**"Table doesn't exist" hatası alıyorsanız:**
- Kurulum scriptini tekrar çalıştırın
- Veritabanı izinlerini kontrol edin

## Güvenlik

✅ Kurulum tamamlandıktan sonra ilk girişten şifrenizi değiştirin!
✅ Production ortamında `display_errors`'ı kapatın
✅ `install.php` ve `install/` klasörünü silmeyi düşünebilirsiniz (opsiyonel)
