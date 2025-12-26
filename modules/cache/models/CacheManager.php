<?php
/**
 * CacheManager - Gelişmiş Önbellek Yönetim Sınıfı
 * 
 * Dosya tabanlı cache, APCu ve OPcache desteği ile performans optimizasyonu sağlar.
 */

class CacheManager {
    
    private static $instance = null;
    
    /**
     * Cache dizini
     * @var string
     */
    private $cache_dir;
    
    /**
     * Cache ayarları
     * @var array
     */
    private $settings = [];
    
    /**
     * Bellek cache (runtime)
     * @var array
     */
    private $memory_cache = [];
    
    /**
     * Cache istatistikleri
     * @var array
     */
    private $stats = [
        'hits' => 0,
        'misses' => 0,
        'writes' => 0
    ];
    
    /**
     * Cache grupları
     * @var array
     */
    private $groups = [
        'default' => [],
        'pages' => [],
        'queries' => [],
        'objects' => [],
        'fragments' => []
    ];
    
    /**
     * Singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->cache_dir = dirname(dirname(dirname(__DIR__))) . '/storage/cache';
        $this->ensureCacheDirectory();
        $this->loadSettings();
    }
    
    private function __clone() {}
    
    /**
     * Cache dizininin var olduğundan emin ol
     */
    private function ensureCacheDirectory() {
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
        
        // Alt gruplar için dizinler
        foreach (array_keys($this->groups) as $group) {
            $group_dir = $this->cache_dir . '/' . $group;
            if (!is_dir($group_dir)) {
                mkdir($group_dir, 0755, true);
            }
        }
        
        // .htaccess dosyası (güvenlik)
        $htaccess = $this->cache_dir . '/.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all\n");
        }
    }
    
    /**
     * Ayarları yükle
     */
    private function loadSettings() {
        $this->settings = [
            'enabled' => true,
            'page_cache' => true,
            'query_cache' => true,
            'object_cache' => true,
            'fragment_cache' => true,
            'default_ttl' => 3600,          // 1 saat
            'page_ttl' => 86400,            // 24 saat
            'query_ttl' => 1800,            // 30 dakika
            'object_ttl' => 3600,           // 1 saat
            'fragment_ttl' => 7200,         // 2 saat
            'use_apcu' => function_exists('apcu_fetch'),
            'compress' => true,
            'min_compress_size' => 1024,    // 1KB üstü sıkıştır
            'exclude_urls' => ['/admin', '/api'],
            'exclude_logged_in' => true,
            'debug_mode' => false
        ];
        
        // Modül ayarlarını birleştir (varsa)
        if (function_exists('get_module_settings')) {
            $saved = get_module_settings('cache');
            if (!empty($saved)) {
                $this->settings = array_merge($this->settings, $saved);
            }
        }
    }
    
    /**
     * Ayar al
     */
    public function getSetting($key, $default = null) {
        return $this->settings[$key] ?? $default;
    }
    
    /**
     * Tüm ayarları al
     */
    public function getSettings() {
        return $this->settings;
    }
    
    /**
     * Ayarları güncelle
     */
    public function updateSettings($settings) {
        $this->settings = array_merge($this->settings, $settings);
    }
    
    // ==================== TEMEL CACHE İŞLEMLERİ ====================
    
    /**
     * Cache'e veri yaz
     * 
     * @param string $key Cache anahtarı
     * @param mixed $value Saklanacak değer
     * @param int $ttl Yaşam süresi (saniye)
     * @param string $group Cache grubu
     * @return bool
     */
    public function set($key, $value, $ttl = null, $group = 'default') {
        if (!$this->settings['enabled']) {
            return false;
        }
        
        $ttl = $ttl ?? $this->settings['default_ttl'];
        $cache_key = $this->buildKey($key, $group);
        
        // Bellek cache'e yaz
        $this->memory_cache[$cache_key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        // APCu kullan (varsa)
        if ($this->settings['use_apcu'] && function_exists('apcu_store')) {
            apcu_store($cache_key, $value, $ttl);
        }
        
        // Dosyaya yaz
        $result = $this->writeToFile($cache_key, $value, $ttl, $group);
        
        if ($result) {
            $this->stats['writes']++;
        }
        
        return $result;
    }
    
    /**
     * Cache'den veri oku
     * 
     * @param string $key Cache anahtarı
     * @param string $group Cache grubu
     * @param mixed $default Varsayılan değer
     * @return mixed
     */
    public function get($key, $group = 'default', $default = null) {
        if (!$this->settings['enabled']) {
            return $default;
        }
        
        $cache_key = $this->buildKey($key, $group);
        
        // Önce bellek cache'e bak
        if (isset($this->memory_cache[$cache_key])) {
            $cached = $this->memory_cache[$cache_key];
            if ($cached['expires'] > time()) {
                $this->stats['hits']++;
                return $cached['value'];
            }
            unset($this->memory_cache[$cache_key]);
        }
        
        // APCu'dan oku (varsa)
        if ($this->settings['use_apcu'] && function_exists('apcu_fetch')) {
            $success = false;
            $value = apcu_fetch($cache_key, $success);
            if ($success) {
                $this->stats['hits']++;
                $this->memory_cache[$cache_key] = [
                    'value' => $value,
                    'expires' => time() + 60 // Bellekte kısa süre tut
                ];
                return $value;
            }
        }
        
        // Dosyadan oku
        $value = $this->readFromFile($cache_key, $group);
        
        if ($value !== null) {
            $this->stats['hits']++;
            return $value;
        }
        
        $this->stats['misses']++;
        return $default;
    }
    
    /**
     * Cache var mı kontrol et
     */
    public function has($key, $group = 'default') {
        return $this->get($key, $group) !== null;
    }
    
    /**
     * Cache'den sil
     */
    public function delete($key, $group = 'default') {
        $cache_key = $this->buildKey($key, $group);
        
        // Bellekten sil
        unset($this->memory_cache[$cache_key]);
        
        // APCu'dan sil
        if ($this->settings['use_apcu'] && function_exists('apcu_delete')) {
            apcu_delete($cache_key);
        }
        
        // Dosyayı sil
        $file = $this->getFilePath($cache_key, $group);
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return true;
    }
    
    /**
     * Grubu temizle
     */
    public function clearGroup($group) {
        // Bellekten temizle
        foreach ($this->memory_cache as $key => $value) {
            if (strpos($key, $group . '_') === 0) {
                unset($this->memory_cache[$key]);
            }
        }
        
        // APCu'dan temizle
        if ($this->settings['use_apcu'] && function_exists('apcu_delete')) {
            $iterator = new APCUIterator('/^' . preg_quote($group . '_', '/') . '/');
            foreach ($iterator as $item) {
                apcu_delete($item['key']);
            }
        }
        
        // Dosyaları temizle
        $group_dir = $this->cache_dir . '/' . $group;
        if (is_dir($group_dir)) {
            $this->clearDirectory($group_dir);
        }
        
        return true;
    }
    
    /**
     * Tüm cache'i temizle
     */
    public function flush() {
        // Bellek cache'i temizle
        $this->memory_cache = [];
        
        // APCu temizle
        if ($this->settings['use_apcu'] && function_exists('apcu_clear_cache')) {
            apcu_clear_cache();
        }
        
        // Tüm dosyaları temizle
        foreach (array_keys($this->groups) as $group) {
            $this->clearGroup($group);
        }
        
        return true;
    }
    
    // ==================== SAYFA CACHE ====================
    
    /**
     * Sayfa cache başlat
     */
    public function startPageCache() {
        if (!$this->settings['page_cache']) {
            return false;
        }
        
        // Admin sayfalarını cache'leme
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        foreach ($this->settings['exclude_urls'] as $exclude) {
            if (strpos($request_uri, $exclude) !== false) {
                return false;
            }
        }
        
        // Giriş yapmış kullanıcıları cache'leme (opsiyonel)
        if ($this->settings['exclude_logged_in'] && $this->isUserLoggedIn()) {
            return false;
        }
        
        // POST isteklerini cache'leme
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return false;
        }
        
        // Cache var mı kontrol et
        $page_key = $this->getPageKey();
        $cached = $this->get($page_key, 'pages');
        
        if ($cached !== null) {
            // Cache header'ları ekle
            header('X-Cache: HIT');
            header('X-Cache-Time: ' . date('c'));
            
            echo $cached;
            return true;
        }
        
        // Output buffering başlat
        ob_start();
        return false;
    }
    
    /**
     * Sayfa cache kaydet
     */
    public function endPageCache() {
        if (!$this->settings['page_cache']) {
            return;
        }
        
        // Admin sayfalarını cache'leme
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        foreach ($this->settings['exclude_urls'] as $exclude) {
            if (strpos($request_uri, $exclude) !== false) {
                return;
            }
        }
        
        // Output buffer'ı al
        $content = ob_get_contents();
        
        if (!empty($content)) {
            $page_key = $this->getPageKey();
            $this->set($page_key, $content, $this->settings['page_ttl'], 'pages');
            
            // Cache header'ları ekle
            header('X-Cache: MISS');
        }
    }
    
    /**
     * Sayfa cache anahtarını oluştur
     */
    private function getPageKey() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $query = $_SERVER['QUERY_STRING'] ?? '';
        
        return md5($uri . '?' . $query);
    }
    
    /**
     * Kullanıcı giriş yapmış mı kontrol et
     */
    private function isUserLoggedIn() {
        return isset($_SESSION['user_id']) || isset($_SESSION['admin_logged_in']);
    }
    
    // ==================== SORGU CACHE ====================
    
    /**
     * Sorgu sonucunu cache'le
     */
    public function cacheQuery($query, $result, $ttl = null) {
        if (!$this->settings['query_cache']) {
            return false;
        }
        
        $ttl = $ttl ?? $this->settings['query_ttl'];
        $key = $this->getQueryKey($query);
        
        return $this->set($key, $result, $ttl, 'queries');
    }
    
    /**
     * Cache'li sorgu sonucunu al
     */
    public function getCachedQuery($query) {
        if (!$this->settings['query_cache']) {
            return null;
        }
        
        $key = $this->getQueryKey($query);
        return $this->get($key, 'queries');
    }
    
    /**
     * Sorgu cache anahtarını oluştur
     */
    private function getQueryKey($query) {
        if (is_array($query)) {
            $query = json_encode($query);
        }
        return md5($query);
    }
    
    /**
     * Sorgu cache'ini temizle (tablo bazlı)
     */
    public function invalidateQueryCache($table = null) {
        if ($table) {
            // Belirli bir tabloyla ilgili cache'leri temizle
            // Not: Bu basitleştirilmiş bir implementasyon
            $this->clearGroup('queries');
        } else {
            $this->clearGroup('queries');
        }
        
        return true;
    }
    
    // ==================== FRAGMENT CACHE ====================
    
    /**
     * Fragment cache başlat
     * 
     * @param string $key Fragment anahtarı
     * @param int $ttl Yaşam süresi
     * @return bool Cache var mı
     */
    public function startFragment($key, $ttl = null) {
        if (!$this->settings['fragment_cache']) {
            ob_start();
            return false;
        }
        
        $cached = $this->get($key, 'fragments');
        
        if ($cached !== null) {
            echo $cached;
            return true;
        }
        
        ob_start();
        return false;
    }
    
    /**
     * Fragment cache bitir
     */
    public function endFragment($key, $ttl = null) {
        $content = ob_get_flush();
        
        if ($this->settings['fragment_cache'] && !empty($content)) {
            $ttl = $ttl ?? $this->settings['fragment_ttl'];
            $this->set($key, $content, $ttl, 'fragments');
        }
    }
    
    // ==================== OBJECT CACHE ====================
    
    /**
     * Nesneyi cache'le
     */
    public function cacheObject($key, $object, $ttl = null) {
        if (!$this->settings['object_cache']) {
            return false;
        }
        
        $ttl = $ttl ?? $this->settings['object_ttl'];
        return $this->set($key, $object, $ttl, 'objects');
    }
    
    /**
     * Cache'li nesneyi al
     */
    public function getObject($key, $default = null) {
        if (!$this->settings['object_cache']) {
            return $default;
        }
        
        return $this->get($key, 'objects', $default);
    }
    
    // ==================== HELPER METODLAR ====================
    
    /**
     * Cache anahtarı oluştur
     */
    private function buildKey($key, $group) {
        return $group . '_' . md5($key);
    }
    
    /**
     * Dosya yolunu al
     */
    private function getFilePath($cache_key, $group) {
        // Alt dizinler oluştur (performans için)
        $subdir = substr($cache_key, 0, 2);
        $dir = $this->cache_dir . '/' . $group . '/' . $subdir;
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        return $dir . '/' . $cache_key . '.cache';
    }
    
    /**
     * Dosyaya yaz
     */
    private function writeToFile($cache_key, $value, $ttl, $group) {
        $file = $this->getFilePath($cache_key, $group);
        
        $data = [
            'expires' => time() + $ttl,
            'created' => time(),
            'data' => $value
        ];
        
        $content = serialize($data);
        
        // Sıkıştırma
        if ($this->settings['compress'] && strlen($content) > $this->settings['min_compress_size']) {
            $compressed = gzcompress($content, 6);
            if ($compressed !== false) {
                $content = 'gz:' . $compressed;
            }
        }
        
        return file_put_contents($file, $content, LOCK_EX) !== false;
    }
    
    /**
     * Dosyadan oku
     */
    private function readFromFile($cache_key, $group) {
        $file = $this->getFilePath($cache_key, $group);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $content = @file_get_contents($file);
        
        if ($content === false) {
            return null;
        }
        
        // Sıkıştırma açma
        if (strpos($content, 'gz:') === 0) {
            $content = @gzuncompress(substr($content, 3));
            if ($content === false) {
                @unlink($file);
                return null;
            }
        }
        
        $data = @unserialize($content);
        
        if (!$data || !isset($data['expires'])) {
            @unlink($file);
            return null;
        }
        
        // Süresi dolmuş mu kontrol et
        if ($data['expires'] < time()) {
            @unlink($file);
            return null;
        }
        
        return $data['data'];
    }
    
    /**
     * Dizini temizle
     */
    private function clearDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $file) {
            if ($file->isDir()) {
                @rmdir($file->getRealPath());
            } else {
                @unlink($file->getRealPath());
            }
        }
    }
    
    // ==================== İSTATİSTİKLER ====================
    
    /**
     * Cache istatistiklerini al
     */
    public function getStats() {
        $stats = $this->stats;
        
        // Hit oranı hesapla
        $total = $stats['hits'] + $stats['misses'];
        $stats['hit_rate'] = $total > 0 ? round(($stats['hits'] / $total) * 100, 2) : 0;
        
        // Disk kullanımı
        $stats['disk_usage'] = $this->getDiskUsage();
        $stats['file_count'] = $this->getFileCount();
        
        // APCu bilgileri
        if ($this->settings['use_apcu'] && function_exists('apcu_cache_info')) {
            $apcu_info = @apcu_cache_info(true);
            if ($apcu_info) {
                $stats['apcu'] = [
                    'memory_size' => $apcu_info['mem_size'] ?? 0,
                    'num_entries' => $apcu_info['num_entries'] ?? 0,
                    'hits' => $apcu_info['num_hits'] ?? 0,
                    'misses' => $apcu_info['num_misses'] ?? 0
                ];
            }
        }
        
        // OPcache bilgileri
        if (function_exists('opcache_get_status')) {
            $opcache_status = @opcache_get_status(false);
            if ($opcache_status) {
                $stats['opcache'] = [
                    'enabled' => $opcache_status['opcache_enabled'] ?? false,
                    'memory_usage' => $opcache_status['memory_usage'] ?? [],
                    'scripts' => $opcache_status['opcache_statistics']['num_cached_scripts'] ?? 0,
                    'hits' => $opcache_status['opcache_statistics']['hits'] ?? 0,
                    'misses' => $opcache_status['opcache_statistics']['misses'] ?? 0,
                    'hit_rate' => $opcache_status['opcache_statistics']['opcache_hit_rate'] ?? 0
                ];
            }
        }
        
        // Grup bazlı istatistikler
        $stats['groups'] = [];
        foreach (array_keys($this->groups) as $group) {
            $group_dir = $this->cache_dir . '/' . $group;
            $stats['groups'][$group] = [
                'size' => $this->getDirectorySize($group_dir),
                'files' => $this->countFilesInDirectory($group_dir)
            ];
        }
        
        return $stats;
    }
    
    /**
     * Disk kullanımını hesapla
     */
    private function getDiskUsage() {
        return $this->getDirectorySize($this->cache_dir);
    }
    
    /**
     * Dizin boyutunu hesapla
     */
    private function getDirectorySize($dir) {
        $size = 0;
        
        if (!is_dir($dir)) {
            return $size;
        }
        
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        
        return $size;
    }
    
    /**
     * Toplam dosya sayısını al
     */
    private function getFileCount() {
        return $this->countFilesInDirectory($this->cache_dir);
    }
    
    /**
     * Dizindeki dosya sayısını say
     */
    private function countFilesInDirectory($dir) {
        $count = 0;
        
        if (!is_dir($dir)) {
            return $count;
        }
        
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'cache') {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Süresi dolmuş cache'leri temizle
     */
    public function cleanup() {
        $cleaned = 0;
        
        foreach (array_keys($this->groups) as $group) {
            $group_dir = $this->cache_dir . '/' . $group;
            
            if (!is_dir($group_dir)) {
                continue;
            }
            
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($group_dir, RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($iterator as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'cache') {
                    continue;
                }
                
                $content = @file_get_contents($file->getRealPath());
                if ($content === false) {
                    continue;
                }
                
                // Sıkıştırma açma
                if (strpos($content, 'gz:') === 0) {
                    $content = @gzuncompress(substr($content, 3));
                }
                
                $data = @unserialize($content);
                
                // Geçersiz veya süresi dolmuş
                if (!$data || !isset($data['expires']) || $data['expires'] < time()) {
                    @unlink($file->getRealPath());
                    $cleaned++;
                }
            }
        }
        
        // Boş dizinleri temizle
        $this->cleanEmptyDirectories($this->cache_dir);
        
        return $cleaned;
    }
    
    /**
     * Boş dizinleri temizle
     */
    private function cleanEmptyDirectories($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->cleanEmptyDirectories($path);
                if (count(array_diff(scandir($path), ['.', '..'])) === 0) {
                    @rmdir($path);
                }
            }
        }
    }
    
    // ==================== OPCACHE YÖNETİMİ ====================
    
    /**
     * OPcache durumunu al
     */
    public function getOpcacheStatus() {
        if (!function_exists('opcache_get_status')) {
            return null;
        }
        
        return @opcache_get_status();
    }
    
    /**
     * OPcache temizle
     */
    public function clearOpcache() {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }
        return false;
    }
    
    /**
     * Belirli bir dosyayı OPcache'den sil
     */
    public function invalidateOpcacheFile($file) {
        if (function_exists('opcache_invalidate')) {
            return opcache_invalidate($file, true);
        }
        return false;
    }
    
    // ==================== APCU YÖNETİMİ ====================
    
    /**
     * APCu durumunu al
     */
    public function getApcuStatus() {
        if (!function_exists('apcu_cache_info')) {
            return null;
        }
        
        return @apcu_cache_info(true);
    }
    
    /**
     * APCu temizle
     */
    public function clearApcu() {
        if (function_exists('apcu_clear_cache')) {
            return apcu_clear_cache();
        }
        return false;
    }
    
    /**
     * Cache dizinini al
     */
    public function getCacheDir() {
        return $this->cache_dir;
    }
    
    /**
     * Boyutu okunabilir formata çevir
     */
    public static function formatSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

// ==================== GLOBAL HELPER FUNCTIONS ====================

/**
 * Cache instance'ını döndür
 */
function cache() {
    return CacheManager::getInstance();
}

/**
 * Cache'e yaz
 */
function cache_set($key, $value, $ttl = null, $group = 'default') {
    return CacheManager::getInstance()->set($key, $value, $ttl, $group);
}

/**
 * Cache'den oku
 */
function cache_get($key, $group = 'default', $default = null) {
    return CacheManager::getInstance()->get($key, $group, $default);
}

/**
 * Cache'den sil
 */
function cache_delete($key, $group = 'default') {
    return CacheManager::getInstance()->delete($key, $group);
}

/**
 * Tüm cache'i temizle
 */
function cache_flush() {
    return CacheManager::getInstance()->flush();
}

/**
 * Remember pattern - varsa cache'den al, yoksa callback'i çalıştır ve cache'le
 */
function cache_remember($key, $ttl, $callback, $group = 'default') {
    $cache = CacheManager::getInstance();
    $value = $cache->get($key, $group);
    
    if ($value !== null) {
        return $value;
    }
    
    $value = $callback();
    $cache->set($key, $value, $ttl, $group);
    
    return $value;
}


