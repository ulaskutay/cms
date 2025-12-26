# Cache Modülü

Performans optimizasyonu için gelişmiş önbellek yönetim sistemi.

## Özellikler

- **Dosya Tabanlı Cache**: Kalıcı cache depolama
- **Sayfa Cache**: Tam HTML çıktısını önbelleğe alma
- **Sorgu Cache**: Veritabanı sorgu sonuçlarını cache'leme
- **Object Cache**: PHP nesnelerini cache'leme
- **Fragment Cache**: Sayfa bileşenlerini cache'leme
- **APCu Desteği**: Bellek tabanlı hızlı cache (opsiyonel)
- **OPcache Yönetimi**: PHP bytecode cache kontrolü
- **Sıkıştırma**: Gzip ile cache dosyalarını sıkıştırma

## Kurulum

Modül, CMS modül sistemi üzerinden otomatik olarak yüklenir. Admin panelinden Modüller sayfasına giderek aktif edebilirsiniz.

## Kullanım

### Temel Cache İşlemleri

```php
// Cache'e yaz
cache_set('my_key', $value, 3600); // 1 saat TTL

// Cache'den oku
$value = cache_get('my_key');

// Cache'den sil
cache_delete('my_key');

// Tüm cache'i temizle
cache_flush();
```

### Remember Pattern

```php
// Varsa cache'den al, yoksa callback'i çalıştır ve cache'le
$users = cache_remember('all_users', 3600, function() {
    return User::all();
});
```

### Grup Bazlı Cache

```php
// Belirli bir gruba yaz
cache_set('user_1', $user, 3600, 'objects');

// Gruptan oku
$user = cache_get('user_1', 'objects');
```

### Fragment Cache

```php
// View içinde fragment cache kullanımı
<?php if (!cache()->startFragment('sidebar_widget', 7200)): ?>
    <div class="widget">
        <!-- Widget içeriği -->
    </div>
<?php cache()->endFragment('sidebar_widget'); endif; ?>
```

### Sorgu Cache

```php
// Sorgu sonucunu cache'le
cache()->cacheQuery($sql, $result, 1800);

// Cache'li sorguyu al
$result = cache()->getCachedQuery($sql);
```

## Cache Grupları

| Grup | Açıklama | Varsayılan TTL |
|------|----------|----------------|
| `default` | Genel amaçlı cache | 1 saat |
| `pages` | Sayfa HTML çıktıları | 24 saat |
| `queries` | Veritabanı sorguları | 30 dakika |
| `objects` | PHP nesneleri | 1 saat |
| `fragments` | Sayfa parçaları | 2 saat |

## Ayarlar

Admin panelinden **Cache > Ayarlar** sayfasına giderek şunları yapılandırabilirsiniz:

- Cache sistemini etkinleştirme/devre dışı bırakma
- Her cache türü için TTL (yaşam süresi) ayarlama
- Hariç tutulacak URL'leri belirleme
- Sıkıştırma seçenekleri
- APCu kullanımı
- Otomatik temizlik

## API

### CacheManager Sınıfı

```php
$cache = CacheManager::getInstance();

// Temel işlemler
$cache->set($key, $value, $ttl, $group);
$cache->get($key, $group, $default);
$cache->has($key, $group);
$cache->delete($key, $group);

// Grup işlemleri
$cache->clearGroup($group);
$cache->flush();

// İstatistikler
$stats = $cache->getStats();

// Temizlik
$cleaned = $cache->cleanup();

// OPcache
$cache->clearOpcache();
$cache->getOpcacheStatus();

// APCu
$cache->clearApcu();
$cache->getApcuStatus();
```

## Performans İpuçları

1. **APCu Kullanın**: Sunucunuzda APCu varsa aktif edin
2. **Uygun TTL Seçin**: Çok kısa = verimli değil, çok uzun = eski veri
3. **Sıkıştırmayı Açın**: Disk alanından tasarruf sağlar
4. **Otomatik Temizliği Aktif Tutun**: Düşük olasılıkla bile olsa temizlik yapılır

## Lisans

Bu modül CMS ile birlikte lisanslanmıştır.


