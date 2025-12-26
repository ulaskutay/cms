<?php
/**
 * Cache Dashboard View
 */

// Helper function for formatting
$formatSize = function($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
};

$formatNumber = function($num) {
    return number_format($num, 0, ',', '.');
};

// Flash mesajları
$flashMessage = $_SESSION['flash_message'] ?? null;
$flashType = $_SESSION['flash_type'] ?? 'info';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>

<link rel="stylesheet" href="<?= admin_asset('css/cache-dashboard.css') ?>">

<div class="cache-dashboard">
    <!-- Başlık -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <span class="material-icons-round">cached</span>
                Cache Yönetimi
            </h1>
            <p class="page-subtitle">Önbellek durumunu izleyin ve yönetin</p>
        </div>
        <div class="header-actions">
            <a href="<?= admin_url('module/cache/settings') ?>" class="btn btn-secondary">
                <span class="material-icons-round">settings</span>
                Ayarlar
            </a>
            <a href="<?= admin_url('module/cache/clear_all') ?>" class="btn btn-danger" onclick="return confirm('Tüm önbellek temizlenecek. Emin misiniz?')">
                <span class="material-icons-round">delete_sweep</span>
                Tümünü Temizle
            </a>
        </div>
    </div>
    
    <?php if ($flashMessage): ?>
    <div class="alert alert-<?= $flashType ?>">
        <span class="material-icons-round">
            <?= $flashType === 'success' ? 'check_circle' : ($flashType === 'error' ? 'error' : 'info') ?>
        </span>
        <span><?= htmlspecialchars($flashMessage) ?></span>
    </div>
    <?php endif; ?>
    
    <!-- Durum Kartları -->
    <div class="stats-grid">
        <!-- Cache Durumu -->
        <div class="stat-card <?= ($settings['enabled'] ?? true) ? 'active' : 'inactive' ?>">
            <div class="stat-icon">
                <span class="material-icons-round"><?= ($settings['enabled'] ?? true) ? 'check_circle' : 'cancel' ?></span>
            </div>
            <div class="stat-content">
                <span class="stat-value"><?= ($settings['enabled'] ?? true) ? 'Aktif' : 'Pasif' ?></span>
                <span class="stat-label">Cache Durumu</span>
            </div>
        </div>
        
        <!-- Hit Oranı -->
        <div class="stat-card">
            <div class="stat-icon hit-rate">
                <span class="material-icons-round">speed</span>
            </div>
            <div class="stat-content">
                <span class="stat-value"><?= $stats['hit_rate'] ?? 0 ?>%</span>
                <span class="stat-label">Hit Oranı</span>
            </div>
        </div>
        
        <!-- Disk Kullanımı -->
        <div class="stat-card">
            <div class="stat-icon disk">
                <span class="material-icons-round">storage</span>
            </div>
            <div class="stat-content">
                <span class="stat-value"><?= $formatSize($stats['disk_usage'] ?? 0) ?></span>
                <span class="stat-label">Disk Kullanımı</span>
            </div>
        </div>
        
        <!-- Dosya Sayısı -->
        <div class="stat-card">
            <div class="stat-icon files">
                <span class="material-icons-round">description</span>
            </div>
            <div class="stat-content">
                <span class="stat-value"><?= $formatNumber($stats['file_count'] ?? 0) ?></span>
                <span class="stat-label">Cache Dosyası</span>
            </div>
        </div>
    </div>
    
    <!-- Ana İçerik -->
    <div class="dashboard-grid">
        <!-- Cache Grupları -->
        <div class="dashboard-card groups-card">
            <div class="card-header">
                <h3>
                    <span class="material-icons-round">folder</span>
                    Cache Grupları
                </h3>
            </div>
            <div class="card-body">
                <div class="groups-list">
                    <?php 
                    $groupIcons = [
                        'default' => 'inventory_2',
                        'pages' => 'web',
                        'queries' => 'database',
                        'objects' => 'data_object',
                        'fragments' => 'widgets'
                    ];
                    $groupNames = [
                        'default' => 'Varsayılan',
                        'pages' => 'Sayfa Cache',
                        'queries' => 'Sorgu Cache',
                        'objects' => 'Object Cache',
                        'fragments' => 'Fragment Cache'
                    ];
                    foreach (($stats['groups'] ?? []) as $group => $groupStats): 
                    ?>
                    <div class="group-item">
                        <div class="group-info">
                            <span class="material-icons-round"><?= $groupIcons[$group] ?? 'folder' ?></span>
                            <div class="group-details">
                                <span class="group-name"><?= $groupNames[$group] ?? ucfirst($group) ?></span>
                                <span class="group-stats">
                                    <?= $formatNumber($groupStats['files'] ?? 0) ?> dosya • 
                                    <?= $formatSize($groupStats['size'] ?? 0) ?>
                                </span>
                            </div>
                        </div>
                        <a href="<?= admin_url('module/cache/clear_group/' . $group) ?>" 
                           class="btn btn-sm btn-outline"
                           onclick="return confirm('Bu gruptaki tüm önbellek temizlenecek. Emin misiniz?')">
                            <span class="material-icons-round">delete</span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Hızlı İşlemler -->
        <div class="dashboard-card actions-card">
            <div class="card-header">
                <h3>
                    <span class="material-icons-round">bolt</span>
                    Hızlı İşlemler
                </h3>
            </div>
            <div class="card-body">
                <div class="actions-list">
                    <a href="<?= admin_url('module/cache/cleanup') ?>" class="action-item">
                        <div class="action-icon cleanup">
                            <span class="material-icons-round">auto_delete</span>
                        </div>
                        <div class="action-info">
                            <span class="action-title">Süresi Dolmuşları Temizle</span>
                            <span class="action-desc">Geçersiz cache dosyalarını kaldır</span>
                        </div>
                    </a>
                    
                    <a href="<?= admin_url('module/cache/clear_opcache') ?>" class="action-item">
                        <div class="action-icon opcache">
                            <span class="material-icons-round">memory</span>
                        </div>
                        <div class="action-info">
                            <span class="action-title">OPcache Temizle</span>
                            <span class="action-desc">PHP bytecode önbelleğini temizle</span>
                        </div>
                    </a>
                    
                    <?php if (function_exists('apcu_fetch')): ?>
                    <a href="<?= admin_url('module/cache/clear_apcu') ?>" class="action-item">
                        <div class="action-icon apcu">
                            <span class="material-icons-round">dynamic_form</span>
                        </div>
                        <div class="action-info">
                            <span class="action-title">APCu Temizle</span>
                            <span class="action-desc">User cache önbelleğini temizle</span>
                        </div>
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?= admin_url('module/cache/clear_all') ?>" class="action-item danger" onclick="return confirm('Tüm önbellek temizlenecek. Emin misiniz?')">
                        <div class="action-icon danger">
                            <span class="material-icons-round">delete_forever</span>
                        </div>
                        <div class="action-info">
                            <span class="action-title">Tüm Cache'i Temizle</span>
                            <span class="action-desc">Tüm önbellek verilerini sil</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- OPcache Durumu -->
        <?php if ($opcacheStatus && ($opcacheStatus['opcache_enabled'] ?? false)): ?>
        <div class="dashboard-card opcache-card">
            <div class="card-header">
                <h3>
                    <span class="material-icons-round">memory</span>
                    OPcache Durumu
                </h3>
                <span class="status-badge active">Aktif</span>
            </div>
            <div class="card-body">
                <?php 
                $memUsage = $opcacheStatus['memory_usage'] ?? [];
                $opcacheStats = $opcacheStatus['opcache_statistics'] ?? [];
                $usedMem = $memUsage['used_memory'] ?? 0;
                $freeMem = $memUsage['free_memory'] ?? 0;
                $totalMem = $usedMem + $freeMem;
                $usedPercent = $totalMem > 0 ? round(($usedMem / $totalMem) * 100) : 0;
                ?>
                <div class="opcache-stats">
                    <div class="opcache-memory">
                        <div class="memory-bar">
                            <div class="memory-used" style="width: <?= $usedPercent ?>%"></div>
                        </div>
                        <div class="memory-info">
                            <span><?= $formatSize($usedMem) ?> kullanılıyor</span>
                            <span><?= $formatSize($freeMem) ?> boş</span>
                        </div>
                    </div>
                    <div class="opcache-details">
                        <div class="detail-item">
                            <span class="detail-label">Önbelleklenen Scriptler</span>
                            <span class="detail-value"><?= $formatNumber($opcacheStats['num_cached_scripts'] ?? 0) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Hit Sayısı</span>
                            <span class="detail-value"><?= $formatNumber($opcacheStats['hits'] ?? 0) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Miss Sayısı</span>
                            <span class="detail-value"><?= $formatNumber($opcacheStats['misses'] ?? 0) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Hit Oranı</span>
                            <span class="detail-value"><?= round($opcacheStats['opcache_hit_rate'] ?? 0, 2) ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="dashboard-card opcache-card inactive">
            <div class="card-header">
                <h3>
                    <span class="material-icons-round">memory</span>
                    OPcache Durumu
                </h3>
                <span class="status-badge inactive">Pasif</span>
            </div>
            <div class="card-body">
                <div class="empty-state">
                    <span class="material-icons-round">info</span>
                    <p>OPcache etkin değil veya bu sunucuda kullanılamıyor.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- APCu Durumu -->
        <?php if ($apcuStatus): ?>
        <div class="dashboard-card apcu-card">
            <div class="card-header">
                <h3>
                    <span class="material-icons-round">dynamic_form</span>
                    APCu Durumu
                </h3>
                <span class="status-badge active">Aktif</span>
            </div>
            <div class="card-body">
                <div class="apcu-stats">
                    <div class="detail-item">
                        <span class="detail-label">Bellek Kullanımı</span>
                        <span class="detail-value"><?= $formatSize($apcuStatus['mem_size'] ?? 0) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Kayıt Sayısı</span>
                        <span class="detail-value"><?= $formatNumber($apcuStatus['num_entries'] ?? 0) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Hit Sayısı</span>
                        <span class="detail-value"><?= $formatNumber($apcuStatus['num_hits'] ?? 0) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Miss Sayısı</span>
                        <span class="detail-value"><?= $formatNumber($apcuStatus['num_misses'] ?? 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php elseif (function_exists('apcu_fetch')): ?>
        <div class="dashboard-card apcu-card">
            <div class="card-header">
                <h3>
                    <span class="material-icons-round">dynamic_form</span>
                    APCu Durumu
                </h3>
                <span class="status-badge inactive">Boş</span>
            </div>
            <div class="card-body">
                <div class="empty-state">
                    <span class="material-icons-round">inbox</span>
                    <p>APCu önbelleğinde henüz veri yok.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Session İstatistikleri -->
    <div class="session-stats">
        <div class="session-item">
            <span class="material-icons-round">trending_up</span>
            <span class="session-label">Bu Oturum:</span>
            <span class="session-value">
                <?= $formatNumber($stats['hits'] ?? 0) ?> hit, 
                <?= $formatNumber($stats['misses'] ?? 0) ?> miss, 
                <?= $formatNumber($stats['writes'] ?? 0) ?> yazma
            </span>
        </div>
    </div>
</div>

<style>
.cache-dashboard {
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-content {
    flex: 1;
}

.page-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.dark .page-title {
    color: #f1f1f1;
}

.page-title .material-icons-round {
    font-size: 2rem;
    color: #6366f1;
}

.page-subtitle {
    color: #64748b;
    margin-top: 0.25rem;
    font-size: 0.95rem;
}

.dark .page-subtitle {
    color: #94a3b8;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Butonlar */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    border: none;
}

.btn .material-icons-round {
    font-size: 1.125rem;
}

.btn-secondary {
    background: #f1f5f9;
    color: #475569;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

.dark .btn-secondary {
    background: #374151;
    color: #e5e7eb;
}

.dark .btn-secondary:hover {
    background: #4b5563;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
}

.btn-outline {
    background: transparent;
    border: 1px solid #e2e8f0;
    color: #64748b;
}

.btn-outline:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.dark .btn-outline {
    border-color: #374151;
    color: #9ca3af;
}

.dark .btn-outline:hover {
    background: #374151;
}

/* Alert */
.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 0.9375rem;
}

.alert-success {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.alert-info {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.dark .alert-success {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
}

.dark .alert-error {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
}

.dark .alert-info {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.dark .stat-card {
    background: #1f2937;
    border-color: #374151;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
}

.stat-icon .material-icons-round {
    font-size: 1.75rem;
    color: white;
}

.stat-card.active .stat-icon {
    background: linear-gradient(135deg, #10b981, #059669);
}

.stat-card.inactive .stat-icon {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.stat-icon.hit-rate {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.stat-icon.disk {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.stat-icon.files {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
}

.dark .stat-value {
    color: #f1f1f1;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 0.125rem;
}

.dark .stat-label {
    color: #9ca3af;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

.dashboard-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.dark .dashboard-card {
    background: #1f2937;
    border-color: #374151;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.dark .card-header {
    border-color: #374151;
}

.card-header h3 {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}

.dark .card-header h3 {
    color: #f1f1f1;
}

.card-header h3 .material-icons-round {
    font-size: 1.25rem;
    color: #6366f1;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: #ecfdf5;
    color: #059669;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #dc2626;
}

.dark .status-badge.active {
    background: rgba(16, 185, 129, 0.15);
}

.dark .status-badge.inactive {
    background: rgba(239, 68, 68, 0.15);
}

.card-body {
    padding: 1.25rem 1.5rem;
}

/* Groups List */
.groups-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.group-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.875rem 1rem;
    background: #f8fafc;
    border-radius: 0.625rem;
    transition: all 0.2s;
}

.group-item:hover {
    background: #f1f5f9;
}

.dark .group-item {
    background: #111827;
}

.dark .group-item:hover {
    background: #1f2937;
}

.group-info {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.group-info > .material-icons-round {
    font-size: 1.5rem;
    color: #6366f1;
}

.group-details {
    display: flex;
    flex-direction: column;
}

.group-name {
    font-weight: 600;
    color: #1a1a2e;
    font-size: 0.9375rem;
}

.dark .group-name {
    color: #f1f1f1;
}

.group-stats {
    font-size: 0.8125rem;
    color: #64748b;
    margin-top: 0.125rem;
}

.dark .group-stats {
    color: #9ca3af;
}

/* Actions List */
.actions-list {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 0.625rem;
    background: #f8fafc;
    text-decoration: none;
    transition: all 0.2s;
}

.action-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.action-item.danger:hover {
    background: #fef2f2;
}

.dark .action-item {
    background: #111827;
}

.dark .action-item:hover {
    background: #1f2937;
}

.dark .action-item.danger:hover {
    background: rgba(239, 68, 68, 0.1);
}

.action-icon {
    width: 44px;
    height: 44px;
    border-radius: 0.625rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-icon .material-icons-round {
    font-size: 1.375rem;
    color: white;
}

.action-icon.cleanup {
    background: linear-gradient(135deg, #10b981, #059669);
}

.action-icon.opcache {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.action-icon.apcu {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.action-icon.danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.action-info {
    display: flex;
    flex-direction: column;
}

.action-title {
    font-weight: 600;
    color: #1a1a2e;
    font-size: 0.9375rem;
}

.dark .action-title {
    color: #f1f1f1;
}

.action-desc {
    font-size: 0.8125rem;
    color: #64748b;
    margin-top: 0.125rem;
}

.dark .action-desc {
    color: #9ca3af;
}

/* OPcache Stats */
.opcache-stats {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.opcache-memory {
    background: #f8fafc;
    border-radius: 0.625rem;
    padding: 1rem;
}

.dark .opcache-memory {
    background: #111827;
}

.memory-bar {
    height: 12px;
    background: #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.dark .memory-bar {
    background: #374151;
}

.memory-used {
    height: 100%;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
    border-radius: 6px;
    transition: width 0.3s;
}

.memory-info {
    display: flex;
    justify-content: space-between;
    font-size: 0.8125rem;
    color: #64748b;
}

.dark .memory-info {
    color: #9ca3af;
}

.opcache-details, .apcu-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 0.5rem;
}

.dark .detail-item {
    background: #111827;
}

.detail-label {
    font-size: 0.75rem;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.dark .detail-label {
    color: #9ca3af;
}

.detail-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a1a2e;
}

.dark .detail-value {
    color: #f1f1f1;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem;
    text-align: center;
}

.empty-state .material-icons-round {
    font-size: 2.5rem;
    color: #cbd5e1;
    margin-bottom: 0.75rem;
}

.dark .empty-state .material-icons-round {
    color: #4b5563;
}

.empty-state p {
    color: #64748b;
    font-size: 0.9375rem;
    margin: 0;
}

.dark .empty-state p {
    color: #9ca3af;
}

/* Session Stats */
.session-stats {
    background: white;
    border-radius: 0.75rem;
    padding: 1rem 1.5rem;
    border: 1px solid #e2e8f0;
}

.dark .session-stats {
    background: #1f2937;
    border-color: #374151;
}

.session-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9375rem;
}

.session-item .material-icons-round {
    font-size: 1.25rem;
    color: #6366f1;
}

.session-label {
    color: #64748b;
}

.dark .session-label {
    color: #9ca3af;
}

.session-value {
    color: #1a1a2e;
    font-weight: 500;
}

.dark .session-value {
    color: #f1f1f1;
}
</style>


