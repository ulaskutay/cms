<?php
/**
 * Cache Modül Controller
 * 
 * Performans optimizasyonu için gelişmiş önbellek yönetimi.
 * Sayfa cache, veritabanı sorgu cache, OPcache ve APCu desteği sunar.
 */

require_once __DIR__ . '/models/CacheManager.php';

class CacheModuleController {
    
    private $moduleInfo;
    private $settings;
    private $db;
    private $cache;
    
    /**
     * Constructor
     */
    public function __construct() {
        if (class_exists('Database')) {
            $this->db = Database::getInstance();
        }
        $this->cache = CacheManager::getInstance();
    }
    
    /**
     * Modül bilgilerini ayarla
     */
    public function setModuleInfo($info) {
        $this->moduleInfo = $info;
    }
    
    /**
     * Modül yüklendiğinde
     */
    public function onLoad() {
        $this->loadSettings();
        
        // Hook'ları kaydet
        if (function_exists('add_action')) {
            // Sayfa cache başlat
            add_action('init', [$this, 'initPageCache'], 1);
            
            // Sayfa cache kaydet (shutdown'da)
            add_action('shutdown', [$this, 'savePageCache'], 999);
            
            // Günlük temizlik görevi
            add_action('daily_cleanup', [$this, 'dailyCleanup']);
        }
        
        if (function_exists('add_filter')) {
            // Veritabanı sorgusu öncesi cache kontrolü
            add_filter('pre_query', [$this, 'checkQueryCache']);
        }
    }
    
    /**
     * Modül aktif edildiğinde
     */
    public function onActivate() {
        // Storage dizinini oluştur
        $storage_dir = dirname(dirname(__DIR__)) . '/storage/cache';
        if (!is_dir($storage_dir)) {
            mkdir($storage_dir, 0755, true);
        }
        
        // Varsayılan ayarları kaydet
        $this->saveDefaultSettings();
    }
    
    /**
     * Modül deaktif edildiğinde
     */
    public function onDeactivate() {
        // Tüm cache'i temizle
        $this->cache->flush();
    }
    
    /**
     * Modül silindiğinde
     */
    public function onUninstall() {
        // Cache dizinini sil
        $cache_dir = $this->cache->getCacheDir();
        if (is_dir($cache_dir)) {
            $this->deleteDirectory($cache_dir);
        }
    }
    
    /**
     * Ayarları yükle
     */
    private function loadSettings() {
        if (function_exists('get_module_settings')) {
            $this->settings = get_module_settings('cache');
        }
        
        if (empty($this->settings)) {
            $this->settings = $this->getDefaultSettings();
        }
        
        // Cache manager'a ayarları aktar
        $this->cache->updateSettings($this->settings);
    }
    
    /**
     * Varsayılan ayarlar
     */
    private function getDefaultSettings() {
        return [
            // Genel ayarlar
            'enabled' => true,
            'debug_mode' => false,
            
            // Sayfa cache
            'page_cache' => true,
            'page_ttl' => 86400,
            
            // Sorgu cache
            'query_cache' => true,
            'query_ttl' => 1800,
            
            // Object cache
            'object_cache' => true,
            'object_ttl' => 3600,
            
            // Fragment cache
            'fragment_cache' => true,
            'fragment_ttl' => 7200,
            
            // APCu
            'use_apcu' => function_exists('apcu_fetch'),
            
            // Sıkıştırma
            'compress' => true,
            'min_compress_size' => 1024,
            
            // Hariç tutulacak URL'ler
            'exclude_urls' => "/admin\n/api\n/login\n/logout",
            
            // Giriş yapmış kullanıcıları hariç tut
            'exclude_logged_in' => true,
            
            // Otomatik temizleme
            'auto_cleanup' => true,
            'cleanup_probability' => 5
        ];
    }
    
    /**
     * Varsayılan ayarları kaydet
     */
    private function saveDefaultSettings() {
        if (!class_exists('ModuleLoader')) {
            return;
        }
        
        $defaults = $this->getDefaultSettings();
        ModuleLoader::getInstance()->saveModuleSettings('cache', $defaults);
    }
    
    // ==================== CACHE HOOKS ====================
    
    /**
     * Sayfa cache başlat (init hook)
     */
    public function initPageCache() {
        if (!($this->settings['page_cache'] ?? true)) {
            return;
        }
        
        // Olasılıkla temizlik yap
        if (($this->settings['auto_cleanup'] ?? true)) {
            $probability = $this->settings['cleanup_probability'] ?? 5;
            if (rand(1, 100) <= $probability) {
                $this->cache->cleanup();
            }
        }
        
        // Sayfa cache'i kontrol et
        if ($this->cache->startPageCache()) {
            exit; // Cache'den sunuldu
        }
    }
    
    /**
     * Sayfa cache kaydet (shutdown hook)
     */
    public function savePageCache() {
        $this->cache->endPageCache();
    }
    
    /**
     * Sorgu cache kontrolü (pre_query filter)
     */
    public function checkQueryCache($query) {
        if (!($this->settings['query_cache'] ?? true)) {
            return $query;
        }
        
        // Burada sorgu cache mantığı uygulanabilir
        // Şimdilik query'yi değiştirmeden döndür
        return $query;
    }
    
    /**
     * Günlük temizlik
     */
    public function dailyCleanup() {
        $this->cache->cleanup();
    }
    
    // ==================== ADMIN METHODS ====================
    
    /**
     * Admin ana sayfa (Dashboard)
     */
    public function admin_index() {
        $stats = $this->cache->getStats();
        $opcacheStatus = $this->cache->getOpcacheStatus();
        $apcuStatus = $this->cache->getApcuStatus();
        
        $this->adminView('index', [
            'title' => 'Cache Yönetimi',
            'stats' => $stats,
            'opcacheStatus' => $opcacheStatus,
            'apcuStatus' => $apcuStatus,
            'settings' => $this->settings
        ]);
    }
    
    /**
     * Ayarlar sayfası
     */
    public function admin_settings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveSettings();
            $_SESSION['flash_message'] = 'Cache ayarları kaydedildi';
            $_SESSION['flash_type'] = 'success';
            $this->redirect('settings');
            return;
        }
        
        $this->adminView('settings', [
            'title' => 'Cache Ayarları',
            'settings' => $this->settings
        ]);
    }
    
    /**
     * Ayarları kaydet
     */
    private function saveSettings() {
        $this->settings['enabled'] = isset($_POST['enabled']);
        $this->settings['debug_mode'] = isset($_POST['debug_mode']);
        
        // Sayfa cache
        $this->settings['page_cache'] = isset($_POST['page_cache']);
        $this->settings['page_ttl'] = (int)($_POST['page_ttl'] ?? 86400);
        
        // Sorgu cache
        $this->settings['query_cache'] = isset($_POST['query_cache']);
        $this->settings['query_ttl'] = (int)($_POST['query_ttl'] ?? 1800);
        
        // Object cache
        $this->settings['object_cache'] = isset($_POST['object_cache']);
        $this->settings['object_ttl'] = (int)($_POST['object_ttl'] ?? 3600);
        
        // Fragment cache
        $this->settings['fragment_cache'] = isset($_POST['fragment_cache']);
        $this->settings['fragment_ttl'] = (int)($_POST['fragment_ttl'] ?? 7200);
        
        // APCu
        $this->settings['use_apcu'] = isset($_POST['use_apcu']);
        
        // Sıkıştırma
        $this->settings['compress'] = isset($_POST['compress']);
        $this->settings['min_compress_size'] = (int)($_POST['min_compress_size'] ?? 1024);
        
        // Hariç tutulacak URL'ler
        $this->settings['exclude_urls'] = $_POST['exclude_urls'] ?? '';
        
        // Giriş yapmış kullanıcıları hariç tut
        $this->settings['exclude_logged_in'] = isset($_POST['exclude_logged_in']);
        
        // Otomatik temizleme
        $this->settings['auto_cleanup'] = isset($_POST['auto_cleanup']);
        $this->settings['cleanup_probability'] = (int)($_POST['cleanup_probability'] ?? 5);
        
        ModuleLoader::getInstance()->saveModuleSettings('cache', $this->settings);
        
        // Cache manager'ı güncelle
        $this->cache->updateSettings($this->settings);
    }
    
    /**
     * Tüm cache'i temizle
     */
    public function admin_clear_all() {
        $this->cache->flush();
        
        $_SESSION['flash_message'] = 'Tüm önbellek başarıyla temizlendi';
        $_SESSION['flash_type'] = 'success';
        
        $this->redirect('');
    }
    
    /**
     * Belirli bir grubu temizle
     */
    public function admin_clear_group($group = 'default') {
        $validGroups = ['default', 'pages', 'queries', 'objects', 'fragments'];
        
        if (in_array($group, $validGroups)) {
            $this->cache->clearGroup($group);
            
            $groupNames = [
                'default' => 'Varsayılan',
                'pages' => 'Sayfa',
                'queries' => 'Sorgu',
                'objects' => 'Nesne',
                'fragments' => 'Fragment'
            ];
            
            $_SESSION['flash_message'] = $groupNames[$group] . ' önbelleği temizlendi';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Geçersiz cache grubu';
            $_SESSION['flash_type'] = 'error';
        }
        
        $this->redirect('');
    }
    
    /**
     * OPcache temizle
     */
    public function admin_clear_opcache() {
        if ($this->cache->clearOpcache()) {
            $_SESSION['flash_message'] = 'OPcache başarıyla temizlendi';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'OPcache temizlenemedi veya kullanılamıyor';
            $_SESSION['flash_type'] = 'error';
        }
        
        $this->redirect('');
    }
    
    /**
     * APCu temizle
     */
    public function admin_clear_apcu() {
        if ($this->cache->clearApcu()) {
            $_SESSION['flash_message'] = 'APCu önbelleği temizlendi';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'APCu temizlenemedi veya kullanılamıyor';
            $_SESSION['flash_type'] = 'error';
        }
        
        $this->redirect('');
    }
    
    /**
     * Süresi dolmuş cache'leri temizle
     */
    public function admin_cleanup() {
        $cleaned = $this->cache->cleanup();
        
        $_SESSION['flash_message'] = $cleaned . ' adet süresi dolmuş önbellek dosyası temizlendi';
        $_SESSION['flash_type'] = 'success';
        
        $this->redirect('');
    }
    
    /**
     * Cache durumu JSON API
     */
    public function admin_api_status() {
        header('Content-Type: application/json');
        
        $stats = $this->cache->getStats();
        
        echo json_encode([
            'success' => true,
            'stats' => $stats,
            'settings' => $this->settings
        ]);
        exit;
    }
    
    // ==================== HELPER METHODS ====================
    
    /**
     * Dizini recursive sil
     */
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        
        rmdir($dir);
    }
    
    /**
     * Admin view render
     */
    private function adminView($view, $data = []) {
        $viewPath = __DIR__ . '/views/admin/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            echo "View not found: " . $view;
            return;
        }
        
        extract($data);
        $currentPage = 'module/cache';
        
        include dirname(dirname(__DIR__)) . '/app/views/admin/snippets/header.php';
        ?>
        <div class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
            <?php include dirname(dirname(__DIR__)) . '/app/views/admin/snippets/sidebar.php'; ?>
            <main class="flex-1 p-6 ml-64">
                <?php include $viewPath; ?>
            </main>
        </div>
        <?php
        include dirname(dirname(__DIR__)) . '/app/views/admin/snippets/footer.php';
    }
    
    /**
     * Yönlendirme
     */
    private function redirect($action) {
        $url = admin_url('module/cache/' . $action);
        header("Location: " . $url);
        exit;
    }
}


