<?php
/**
 * Cache Settings View
 */

// Flash mesajları
$flashMessage = $_SESSION['flash_message'] ?? null;
$flashType = $_SESSION['flash_type'] ?? 'info';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

// APCu kontrol
$apcuAvailable = function_exists('apcu_fetch');
?>

<div class="cache-settings">
    <!-- Başlık -->
    <div class="page-header">
        <div class="header-content">
            <a href="<?= admin_url('module/cache') ?>" class="back-link">
                <span class="material-icons-round">arrow_back</span>
            </a>
            <div>
                <h1 class="page-title">Cache Ayarları</h1>
                <p class="page-subtitle">Önbellek davranışlarını yapılandırın</p>
            </div>
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
    
    <form method="POST" class="settings-form">
        <!-- Genel Ayarlar -->
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon">
                    <span class="material-icons-round">tune</span>
                </div>
                <div class="section-info">
                    <h2>Genel Ayarlar</h2>
                    <p>Cache sisteminin temel ayarları</p>
                </div>
            </div>
            <div class="section-content">
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Cache Sistemi</label>
                        <span class="setting-desc">Önbellek sistemini etkinleştir veya devre dışı bırak</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="enabled" <?= ($settings['enabled'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Debug Modu</label>
                        <span class="setting-desc">Cache header'larını ve debug bilgilerini göster</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="debug_mode" <?= ($settings['debug_mode'] ?? false) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Sayfa Cache -->
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon page">
                    <span class="material-icons-round">web</span>
                </div>
                <div class="section-info">
                    <h2>Sayfa Cache</h2>
                    <p>HTML çıktısının önbelleğe alınması</p>
                </div>
            </div>
            <div class="section-content">
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Sayfa Cache</label>
                        <span class="setting-desc">Tam sayfa çıktısını önbelleğe al</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="page_cache" <?= ($settings['page_cache'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Sayfa Cache Süresi (saniye)</label>
                        <span class="setting-desc">Sayfa önbelleğinin ne kadar süre saklanacağı</span>
                    </div>
                    <input type="number" name="page_ttl" value="<?= $settings['page_ttl'] ?? 86400 ?>" class="form-input small" min="60" step="60">
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Giriş Yapmış Kullanıcıları Hariç Tut</label>
                        <span class="setting-desc">Oturum açmış kullanıcılara cache'siz sayfa göster</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="exclude_logged_in" <?= ($settings['exclude_logged_in'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row full-width">
                    <div class="setting-info">
                        <label>Hariç Tutulacak URL'ler</label>
                        <span class="setting-desc">Her satıra bir URL yazın (önbelleğe alınmayacak)</span>
                    </div>
                    <textarea name="exclude_urls" class="form-textarea" rows="4"><?= htmlspecialchars($settings['exclude_urls'] ?? "/admin\n/api\n/login\n/logout") ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Sorgu Cache -->
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon query">
                    <span class="material-icons-round">database</span>
                </div>
                <div class="section-info">
                    <h2>Sorgu Cache</h2>
                    <p>Veritabanı sorgu sonuçlarının önbelleğe alınması</p>
                </div>
            </div>
            <div class="section-content">
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Sorgu Cache</label>
                        <span class="setting-desc">Veritabanı sorgu sonuçlarını önbelleğe al</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="query_cache" <?= ($settings['query_cache'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Sorgu Cache Süresi (saniye)</label>
                        <span class="setting-desc">Sorgu önbelleğinin ne kadar süre saklanacağı</span>
                    </div>
                    <input type="number" name="query_ttl" value="<?= $settings['query_ttl'] ?? 1800 ?>" class="form-input small" min="60" step="60">
                </div>
            </div>
        </div>
        
        <!-- Object Cache -->
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon object">
                    <span class="material-icons-round">data_object</span>
                </div>
                <div class="section-info">
                    <h2>Object Cache</h2>
                    <p>PHP nesnelerinin önbelleğe alınması</p>
                </div>
            </div>
            <div class="section-content">
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Object Cache</label>
                        <span class="setting-desc">PHP nesnelerini önbelleğe al</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="object_cache" <?= ($settings['object_cache'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Object Cache Süresi (saniye)</label>
                        <span class="setting-desc">Nesne önbelleğinin ne kadar süre saklanacağı</span>
                    </div>
                    <input type="number" name="object_ttl" value="<?= $settings['object_ttl'] ?? 3600 ?>" class="form-input small" min="60" step="60">
                </div>
            </div>
        </div>
        
        <!-- Fragment Cache -->
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon fragment">
                    <span class="material-icons-round">widgets</span>
                </div>
                <div class="section-info">
                    <h2>Fragment Cache</h2>
                    <p>Sayfa parçalarının önbelleğe alınması</p>
                </div>
            </div>
            <div class="section-content">
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Fragment Cache</label>
                        <span class="setting-desc">Sayfa bileşenlerini önbelleğe al</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="fragment_cache" <?= ($settings['fragment_cache'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Fragment Cache Süresi (saniye)</label>
                        <span class="setting-desc">Fragment önbelleğinin ne kadar süre saklanacağı</span>
                    </div>
                    <input type="number" name="fragment_ttl" value="<?= $settings['fragment_ttl'] ?? 7200 ?>" class="form-input small" min="60" step="60">
                </div>
            </div>
        </div>
        
        <!-- Gelişmiş Ayarlar -->
        <div class="settings-section">
            <div class="section-header">
                <div class="section-icon advanced">
                    <span class="material-icons-round">settings_applications</span>
                </div>
                <div class="section-info">
                    <h2>Gelişmiş Ayarlar</h2>
                    <p>Performans ve depolama seçenekleri</p>
                </div>
            </div>
            <div class="section-content">
                <div class="setting-row <?= !$apcuAvailable ? 'disabled' : '' ?>">
                    <div class="setting-info">
                        <label>APCu Kullan</label>
                        <span class="setting-desc">
                            <?php if ($apcuAvailable): ?>
                            Bellek tabanlı önbellek için APCu kullan
                            <?php else: ?>
                            APCu bu sunucuda kullanılamıyor
                            <?php endif; ?>
                        </span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="use_apcu" 
                               <?= ($settings['use_apcu'] ?? false) && $apcuAvailable ? 'checked' : '' ?>
                               <?= !$apcuAvailable ? 'disabled' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Sıkıştırma</label>
                        <span class="setting-desc">Cache dosyalarını gzip ile sıkıştır</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="compress" <?= ($settings['compress'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Minimum Sıkıştırma Boyutu (byte)</label>
                        <span class="setting-desc">Bu boyutun üzerindeki veriler sıkıştırılır</span>
                    </div>
                    <input type="number" name="min_compress_size" value="<?= $settings['min_compress_size'] ?? 1024 ?>" class="form-input small" min="256" step="256">
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Otomatik Temizlik</label>
                        <span class="setting-desc">Süresi dolmuş cache'leri otomatik temizle</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="auto_cleanup" <?= ($settings['auto_cleanup'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="setting-row">
                    <div class="setting-info">
                        <label>Temizlik Olasılığı (%)</label>
                        <span class="setting-desc">Her istekte temizlik yapılma olasılığı</span>
                    </div>
                    <input type="number" name="cleanup_probability" value="<?= $settings['cleanup_probability'] ?? 5 ?>" class="form-input small" min="1" max="100">
                </div>
            </div>
        </div>
        
        <!-- Kaydet Butonu -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <span class="material-icons-round">save</span>
                Ayarları Kaydet
            </button>
            <a href="<?= admin_url('module/cache') ?>" class="btn btn-secondary">
                <span class="material-icons-round">close</span>
                İptal
            </a>
        </div>
    </form>
</div>

<style>
.cache-settings {
    max-width: 900px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.back-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 0.625rem;
    color: #64748b;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.back-link:hover {
    background: #f1f5f9;
    color: #1a1a2e;
}

.dark .back-link {
    background: #1f2937;
    border-color: #374151;
    color: #9ca3af;
}

.dark .back-link:hover {
    background: #374151;
    color: #f1f1f1;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
}

.dark .page-title {
    color: #f1f1f1;
}

.page-subtitle {
    color: #64748b;
    margin-top: 0.25rem;
    font-size: 0.9375rem;
}

.dark .page-subtitle {
    color: #9ca3af;
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

.dark .alert-success {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
}

/* Settings Section */
.settings-section {
    background: white;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.dark .settings-section {
    background: #1f2937;
    border-color: #374151;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.dark .section-header {
    background: #111827;
    border-color: #374151;
}

.section-icon {
    width: 48px;
    height: 48px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
}

.section-icon .material-icons-round {
    font-size: 1.5rem;
    color: white;
}

.section-icon.page {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.section-icon.query {
    background: linear-gradient(135deg, #10b981, #059669);
}

.section-icon.object {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.section-icon.fragment {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.section-icon.advanced {
    background: linear-gradient(135deg, #64748b, #475569);
}

.section-info h2 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
}

.dark .section-info h2 {
    color: #f1f1f1;
}

.section-info p {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0.25rem 0 0;
}

.dark .section-info p {
    color: #9ca3af;
}

.section-content {
    padding: 1rem 1.5rem;
}

/* Setting Row */
.setting-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.setting-row:last-child {
    border-bottom: none;
}

.dark .setting-row {
    border-color: #374151;
}

.setting-row.full-width {
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
}

.setting-row.disabled {
    opacity: 0.5;
}

.setting-info {
    flex: 1;
}

.setting-info label {
    display: block;
    font-weight: 600;
    color: #1a1a2e;
    font-size: 0.9375rem;
}

.dark .setting-info label {
    color: #f1f1f1;
}

.setting-desc {
    display: block;
    font-size: 0.8125rem;
    color: #64748b;
    margin-top: 0.25rem;
}

.dark .setting-desc {
    color: #9ca3af;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #cbd5e1;
    border-radius: 28px;
    transition: 0.3s;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

.toggle-switch input:disabled + .toggle-slider {
    opacity: 0.5;
    cursor: not-allowed;
}

.dark .toggle-slider {
    background: #4b5563;
}

/* Form Input */
.form-input {
    padding: 0.625rem 0.875rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.9375rem;
    color: #1a1a2e;
    background: white;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-input.small {
    width: 140px;
    text-align: right;
}

.dark .form-input {
    background: #111827;
    border-color: #374151;
    color: #f1f1f1;
}

.dark .form-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

/* Form Textarea */
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.9375rem;
    color: #1a1a2e;
    background: white;
    font-family: 'JetBrains Mono', monospace;
    resize: vertical;
    transition: all 0.2s;
}

.form-textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.dark .form-textarea {
    background: #111827;
    border-color: #374151;
    color: #f1f1f1;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    border: none;
}

.btn .material-icons-round {
    font-size: 1.125rem;
}

.btn-primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
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
</style>


