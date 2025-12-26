<?php include __DIR__ . '/../snippets/header.php'; ?>

<?php
// Sayfa ayarlarını veritabanından yükle
$contactSettings = $themeManager->getAllPageSettings('contact');
$blogSettings = $themeManager->getAllPageSettings('blog');

// Formları çek (CTA için)
$forms = [];
if (class_exists('Form')) {
    try {
        $formModel = new Form();
        $forms = $formModel->getAll();
    } catch (Exception $e) {
        // Form model yoksa boş bırak
    }
}
?>

<div class="flex flex-col lg:flex-row h-screen overflow-hidden bg-gray-100 dark:bg-[#15202b]">
    <!-- Sidebar Özelleştirici Panel -->
    <aside id="customizer-sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[85vw] sm:w-[380px] lg:w-[380px] flex-shrink-0 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 flex flex-col h-full transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                <a href="<?php echo admin_url('themes'); ?>" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors flex-shrink-0">
                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-lg sm:text-xl">arrow_back</span>
                </a>
                <div class="min-w-0">
                    <h1 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white truncate">Tema Özelleştirici</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo esc_html($theme['name']); ?></p>
                </div>
            </div>
            <button id="save-settings" class="px-3 sm:px-4 py-2 bg-primary text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 min-h-[36px] flex-shrink-0 ml-2">
                <span class="hidden sm:inline">Yayınla</span>
                <span class="sm:hidden material-symbols-outlined">check</span>
            </button>
            <button onclick="toggleCustomizerSidebar()" class="lg:hidden p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors ml-2 flex-shrink-0">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">close</span>
            </button>
        </div>
        
        <!-- Accordion Panels -->
        <div class="flex-1 overflow-y-auto">
            <!-- Logo & Favicon -->
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors min-h-[44px]" onclick="toggleSection(this)">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-xl flex-shrink-0">image</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">Logo & Favicon</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon text-lg sm:text-xl flex-shrink-0">expand_more</span>
                </button>
                <div class="section-content hidden px-3 sm:px-4 pb-3 sm:pb-4 space-y-3 sm:space-y-4">
                    <!-- Site Logo -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Site Logo</label>
                        <div class="flex items-center gap-3">
                            <div id="theme-logo-preview" class="flex-shrink-0 w-20 h-20 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-2 flex items-center justify-center overflow-hidden">
                                <?php 
                                $siteLogo = $themeManager->getThemeOption('site_logo', get_option('site_logo', ''), 'branding');
                                if (!empty($siteLogo)): ?>
                                    <img src="<?php echo esc_url($siteLogo); ?>" alt="Logo" class="max-w-full max-h-full object-contain">
                                <?php else: ?>
                                    <span class="material-symbols-outlined text-gray-400 text-2xl">image</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="hidden" id="theme-site-logo" 
                                    data-group="branding" 
                                    data-key="site_logo"
                                    class="setting-input"
                                    value="<?php echo esc_attr($siteLogo); ?>">
                                <div class="flex gap-2">
                                    <button type="button" onclick="openThemeLogoPicker()" class="flex items-center gap-1 px-3 py-1.5 text-xs bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                        <span class="material-symbols-outlined text-base">perm_media</span>
                                        Seç
                                    </button>
                                    <button type="button" onclick="removeThemeLogo()" class="px-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        Kaldır
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Önerilen: PNG veya SVG, şeffaf arka plan</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                        <div class="flex items-center gap-3">
                            <div id="theme-favicon-preview" class="flex-shrink-0 w-14 h-14 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-2 flex items-center justify-center overflow-hidden">
                                <?php 
                                $siteFavicon = $themeManager->getThemeOption('site_favicon', get_option('site_favicon', ''), 'branding');
                                if (!empty($siteFavicon)): ?>
                                    <img src="<?php echo esc_url($siteFavicon); ?>" alt="Favicon" class="max-w-full max-h-full object-contain">
                                <?php else: ?>
                                    <span class="material-symbols-outlined text-gray-400 text-xl">image</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="hidden" id="theme-site-favicon" 
                                    data-group="branding" 
                                    data-key="site_favicon"
                                    class="setting-input"
                                    value="<?php echo esc_attr($siteFavicon); ?>">
                                <div class="flex gap-2">
                                    <button type="button" onclick="openThemeFaviconPicker()" class="flex items-center gap-1 px-3 py-1.5 text-xs bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                        <span class="material-symbols-outlined text-base">perm_media</span>
                                        Seç
                                    </button>
                                    <button type="button" onclick="removeThemeFavicon()" class="px-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        Kaldır
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Önerilen: 32x32 veya 64x64 PNG/ICO</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Renkler -->
            <?php if (!empty($settings['colors'])): ?>
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors min-h-[44px]" onclick="toggleSection(this)">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-xl flex-shrink-0">palette</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">Renkler</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon text-lg sm:text-xl flex-shrink-0">expand_more</span>
                </button>
                <div class="section-content hidden px-3 sm:px-4 pb-3 sm:pb-4 space-y-3 sm:space-y-4">
                    <?php foreach ($settings['colors'] as $key => $config): ?>
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-gray-700 dark:text-gray-300"><?php echo esc_html($config['label']); ?></label>
                        <div class="flex items-center gap-2">
                            <input type="color" 
                                name="colors[<?php echo $key; ?>]" 
                                value="<?php echo esc_attr($config['value'] ?? $config['default']); ?>"
                                data-group="colors"
                                data-key="<?php echo $key; ?>"
                                class="setting-input w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer"
                                onchange="updatePreview()">
                            <input type="text" 
                                value="<?php echo esc_attr($config['value'] ?? $config['default']); ?>"
                                class="w-24 px-2 py-1.5 text-xs border border-gray-200 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                oninput="this.previousElementSibling.value = this.value; updatePreview()">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tipografi -->
            <?php if (!empty($settings['fonts'])): ?>
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">text_fields</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Tipografi</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
                </button>
                <div class="section-content hidden px-4 pb-4 space-y-4">
                    <?php foreach ($settings['fonts'] as $key => $config): ?>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2"><?php echo esc_html($config['label']); ?></label>
                        <select name="fonts[<?php echo $key; ?>]" 
                            data-group="fonts"
                            data-key="<?php echo $key; ?>"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                            onchange="updatePreview()">
                            <?php foreach ($availableFonts as $fontName => $fontLabel): ?>
                            <option value="<?php echo esc_attr($fontName); ?>" <?php echo ($config['value'] ?? $config['default']) === $fontName ? 'selected' : ''; ?>>
                                <?php echo esc_html($fontLabel); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Özel Ayarlar -->
            <?php if (!empty($settings['custom'])): ?>
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">settings</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Tema Ayarları</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
                </button>
                <div class="section-content hidden px-4 pb-4 space-y-4">
                    <?php foreach ($settings['custom'] as $key => $config): ?>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2"><?php echo esc_html($config['label']); ?></label>
                        
                        <?php if ($config['type'] === 'boolean'): ?>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                name="custom[<?php echo $key; ?>]"
                                data-group="custom"
                                data-key="<?php echo $key; ?>"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php echo ($config['value'] ?? $config['default']) ? 'checked' : ''; ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Etkin</span>
                        </label>
                        
                        <?php elseif ($config['type'] === 'select'): ?>
                        <select name="custom[<?php echo $key; ?>]"
                            data-group="custom"
                            data-key="<?php echo $key; ?>"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                            onchange="updatePreview()">
                            <?php foreach ($config['options'] as $opt): ?>
                            <option value="<?php echo esc_attr($opt); ?>" <?php echo ($config['value'] ?? $config['default']) === $opt ? 'selected' : ''; ?>>
                                <?php echo esc_html(ucfirst($opt)); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <?php else: ?>
                        <input type="text"
                            name="custom[<?php echo $key; ?>]"
                            data-group="custom"
                            data-key="<?php echo $key; ?>"
                            value="<?php echo esc_attr($config['value'] ?? $config['default']); ?>"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                            onchange="updatePreview()">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Footer Ayarları -->
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors min-h-[44px]" onclick="toggleSection(this)">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                        <span class="material-symbols-outlined text-primary text-lg sm:text-xl flex-shrink-0">web</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">Footer Ayarları</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon text-lg sm:text-xl flex-shrink-0">expand_more</span>
                </button>
                <div class="section-content hidden px-3 sm:px-4 pb-3 sm:pb-4 space-y-3 sm:space-y-4">
                    <!-- Footer Göster -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="custom"
                                data-key="footer_show"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php 
                                $footerShowValue = $settings['custom']['footer_show']['value'] ?? $settings['custom']['footer_show']['default'] ?? true;
                                $footerShowChecked = ($footerShowValue === true || $footerShowValue === '1' || $footerShowValue === 1) ? 'checked' : '';
                                echo $footerShowChecked;
                                ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Footer Göster</span>
                        </label>
                    </div>
                    
                    <!-- Footer Kolon Sayısı -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Footer Kolon Sayısı</label>
                        <select data-group="custom"
                            data-key="footer_columns"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                            onchange="updatePreview()">
                            <option value="2" <?php echo (($settings['custom']['footer_columns']['value'] ?? $settings['custom']['footer_columns']['default'] ?? '4') === '2') ? 'selected' : ''; ?>>2 Kolon</option>
                            <option value="3" <?php echo (($settings['custom']['footer_columns']['value'] ?? $settings['custom']['footer_columns']['default'] ?? '4') === '3') ? 'selected' : ''; ?>>3 Kolon</option>
                            <option value="4" <?php echo (($settings['custom']['footer_columns']['value'] ?? $settings['custom']['footer_columns']['default'] ?? '4') === '4') ? 'selected' : ''; ?>>4 Kolon</option>
                        </select>
                    </div>
                    
                    <!-- Footer Arka Plan Rengi -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Footer Arka Plan Rengi</label>
                        <div class="flex items-center gap-2">
                            <input type="color" 
                                data-group="custom"
                                data-key="footer_bg_color"
                                value="<?php echo esc_attr($settings['custom']['footer_bg_color']['value'] ?? $settings['custom']['footer_bg_color']['default'] ?? '#111827'); ?>"
                                class="setting-input w-12 h-10 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer"
                                onchange="updatePreview()">
                            <input type="text" 
                                value="<?php echo esc_attr($settings['custom']['footer_bg_color']['value'] ?? $settings['custom']['footer_bg_color']['default'] ?? '#111827'); ?>"
                                class="flex-1 px-3 py-2 text-xs border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                oninput="this.previousElementSibling.value = this.value; this.previousElementSibling.dispatchEvent(new Event('change')); updatePreview()">
                        </div>
                    </div>
                    
                    <!-- Footer Metin Rengi -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Footer Metin Rengi</label>
                        <div class="flex items-center gap-2">
                            <input type="color" 
                                data-group="custom"
                                data-key="footer_text_color"
                                value="<?php echo esc_attr($settings['custom']['footer_text_color']['value'] ?? $settings['custom']['footer_text_color']['default'] ?? '#ffffff'); ?>"
                                class="setting-input w-12 h-10 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer"
                                onchange="updatePreview()">
                            <input type="text" 
                                value="<?php echo esc_attr($settings['custom']['footer_text_color']['value'] ?? $settings['custom']['footer_text_color']['default'] ?? '#ffffff'); ?>"
                                class="flex-1 px-3 py-2 text-xs border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                oninput="this.previousElementSibling.value = this.value; this.previousElementSibling.dispatchEvent(new Event('change')); updatePreview()">
                        </div>
                    </div>
                    
                    <!-- Copyright Formatı -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Copyright Formatı</label>
                        <select data-group="custom"
                            data-key="footer_copyright_format"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                            onchange="toggleCopyrightFormat(this.value); updatePreview()">
                            <option value="default" <?php echo (($settings['custom']['footer_copyright_format']['value'] ?? $settings['custom']['footer_copyright_format']['default'] ?? 'default') === 'default') ? 'selected' : ''; ?>>Varsayılan Format</option>
                            <option value="custom" <?php echo (($settings['custom']['footer_copyright_format']['value'] ?? $settings['custom']['footer_copyright_format']['default'] ?? 'default') === 'custom') ? 'selected' : ''; ?>>Özel Metin</option>
                        </select>
                    </div>
                    
                    <!-- Varsayılan Format Ayarları -->
                    <div id="copyright-default-format" class="space-y-3">
                        <!-- Copyright Yılı -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Copyright Yılı</label>
                            <select data-group="custom"
                                data-key="footer_copyright_year"
                                class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                onchange="toggleCopyrightYear(this.value); updatePreview()">
                                <option value="auto" <?php echo (($settings['custom']['footer_copyright_year']['value'] ?? $settings['custom']['footer_copyright_year']['default'] ?? 'auto') === 'auto') ? 'selected' : ''; ?>>Otomatik (<?php echo date('Y'); ?>)</option>
                                <option value="manual" <?php echo (($settings['custom']['footer_copyright_year']['value'] ?? $settings['custom']['footer_copyright_year']['default'] ?? 'auto') === 'manual') ? 'selected' : ''; ?>>Manuel</option>
                            </select>
                        </div>
                        
                        <!-- Manuel Yıl -->
                        <div id="copyright-manual-year" style="display: <?php echo (($settings['custom']['footer_copyright_year']['value'] ?? 'auto') === 'manual') ? 'block' : 'none'; ?>;">
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Manuel Yıl</label>
                            <input type="text"
                                data-group="custom"
                                data-key="footer_copyright_year_manual"
                                value="<?php echo esc_attr($settings['custom']['footer_copyright_year_manual']['value'] ?? $settings['custom']['footer_copyright_year_manual']['default'] ?? ''); ?>"
                                class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                placeholder="<?php echo date('Y'); ?>"
                                onchange="updatePreview()">
                        </div>
                        
                        <!-- Copyright Şirket Adı -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Copyright Şirket Adı</label>
                            <input type="text"
                                data-group="custom"
                                data-key="footer_copyright_company"
                                value="<?php echo esc_attr($settings['custom']['footer_copyright_company']['value'] ?? $settings['custom']['footer_copyright_company']['default'] ?? ''); ?>"
                                class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                placeholder="Boş bırakılırsa site adı kullanılır"
                                onchange="updatePreview()">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Boş bırakılırsa site adı kullanılır</p>
                        </div>
                        
                        <!-- Telif Hakkı Metni -->
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Telif Hakkı Metni</label>
                            <input type="text"
                                data-group="custom"
                                data-key="footer_copyright_text"
                                value="<?php echo esc_attr($settings['custom']['footer_copyright_text']['value'] ?? $settings['custom']['footer_copyright_text']['default'] ?? 'Tüm hakları saklıdır.'); ?>"
                                class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                placeholder="Tüm hakları saklıdır."
                                onchange="updatePreview()">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: © [Yıl] [Şirket Adı]. [Metin]</p>
                        </div>
                    </div>
                    
                    <!-- Özel Copyright Metni -->
                    <div id="copyright-custom-format" style="display: <?php echo (($settings['custom']['footer_copyright_format']['value'] ?? 'default') === 'custom') ? 'block' : 'none'; ?>;">
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Özel Copyright Metni</label>
                        <textarea data-group="custom"
                            data-key="footer_copyright_custom"
                            rows="3"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 resize-none"
                            placeholder="© 2023 Şirket Adı. Tüm hakları saklıdır."
                            onchange="updatePreview()"><?php echo esc_html($settings['custom']['footer_copyright_custom']['value'] ?? $settings['custom']['footer_copyright_custom']['default'] ?? ''); ?></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tam copyright metnini buraya yazın. {year} ile yıl, {company} ile şirket adı kullanabilirsiniz.</p>
                    </div>
                    
                    <!-- Sosyal Medya İkonları -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="custom"
                                data-key="footer_show_social"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php 
                                $footerShowSocialValue = $settings['custom']['footer_show_social']['value'] ?? $settings['custom']['footer_show_social']['default'] ?? true;
                                $footerShowSocialChecked = ($footerShowSocialValue === true || $footerShowSocialValue === '1' || $footerShowSocialValue === 1) ? 'checked' : '';
                                echo $footerShowSocialChecked;
                                ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Sosyal Medya İkonları Göster</span>
                        </label>
                    </div>
                    
                    <!-- Footer Menü -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="custom"
                                data-key="footer_show_menu"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php 
                                $footerShowMenuValue = $settings['custom']['footer_show_menu']['value'] ?? $settings['custom']['footer_show_menu']['default'] ?? true;
                                $footerShowMenuChecked = ($footerShowMenuValue === true || $footerShowMenuValue === '1' || $footerShowMenuValue === 1) ? 'checked' : '';
                                echo $footerShowMenuChecked;
                                ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Footer Menü Göster</span>
                        </label>
                    </div>
                    
                    <!-- İletişim Bilgileri -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="custom"
                                data-key="footer_show_contact"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php 
                                $footerShowContactValue = $settings['custom']['footer_show_contact']['value'] ?? $settings['custom']['footer_show_contact']['default'] ?? true;
                                $footerShowContactChecked = ($footerShowContactValue === true || $footerShowContactValue === '1' || $footerShowContactValue === 1) ? 'checked' : '';
                                echo $footerShowContactChecked;
                                ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">İletişim Bilgileri Göster</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Blog Sayfası Ayarları -->
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700" id="blog-settings-section">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">article</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Blog Sayfası</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
                </button>
                <div class="section-content hidden px-4 pb-4 space-y-4">
                    <!-- Sayfa Başına Yazı -->
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Sayfa Başına Yazı</label>
                        <input type="number"
                            data-group="page_blog"
                            data-key="posts_per_page"
                            value="<?php echo esc_attr($blogSettings['posts_per_page'] ?? '10'); ?>"
                            min="1"
                            max="50"
                            class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                            onchange="updatePreview()">
                    </div>
                    
                    <!-- Sidebar Gösterimi -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="page_blog"
                                data-key="show_sidebar"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php echo (!isset($blogSettings['show_sidebar']) || $blogSettings['show_sidebar'] === '1') ? 'checked' : ''; ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Sidebar Göster</span>
                        </label>
                    </div>
                    
                    <!-- Kategori Listesi -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="page_blog"
                                data-key="show_categories"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php echo (!isset($blogSettings['show_categories']) || $blogSettings['show_categories'] === '1') ? 'checked' : ''; ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Kategori Listesi</span>
                        </label>
                    </div>
                    
                    <!-- Yazar Bilgisi -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                data-group="page_blog"
                                data-key="show_author"
                                class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                                <?php echo (!isset($blogSettings['show_author']) || $blogSettings['show_author'] === '1') ? 'checked' : ''; ?>
                                onchange="updatePreview()">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Yazar Bilgisi Göster</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Özel CSS -->
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">code</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Özel CSS</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
                </button>
                <div class="section-content hidden px-4 pb-4">
                    <textarea id="custom-css" 
                        class="w-full h-48 px-3 py-2 text-sm font-mono border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 resize-none"
                        placeholder="/* Özel CSS kodunuz buraya */"><?php echo esc_html($customCss); ?></textarea>
                    <button type="button" onclick="saveCustomCode('css')" class="mt-2 px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        CSS'i Kaydet
                    </button>
                </div>
            </div>
            
            <!-- Özel JS -->
            <div class="customizer-section border-b border-gray-200 dark:border-gray-700">
                <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">javascript</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Özel JavaScript</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
                </button>
                <div class="section-content hidden px-4 pb-4">
                    <textarea id="custom-js"
                        class="w-full h-48 px-3 py-2 text-sm font-mono border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 resize-none"
                        placeholder="// Özel JavaScript kodunuz buraya"><?php echo esc_html($customJs); ?></textarea>
                    <button type="button" onclick="saveCustomCode('js')" class="mt-2 px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        JS'i Kaydet
                    </button>
                </div>
            </div>
        </div>
        
        <!-- İletişim Sayfası Ayarları -->
        <div class="customizer-section border-b border-gray-200 dark:border-gray-700" id="contact-settings-section">
            <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">contact_mail</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">İletişim Sayfası</span>
                </div>
                <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
            </button>
            <div class="section-content hidden px-4 pb-4 space-y-4">
                <!-- Mesaj Gönderin Formu Seçimi -->
                <div class="bg-primary/5 dark:bg-primary/10 p-4 rounded-lg border border-primary/20">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        <span class="material-symbols-outlined text-base align-middle mr-1">mail</span>
                        Mesaj Gönderin Formu
                    </label>
                    <select id="contact-form-id" 
                        data-group="page_contact"
                        data-key="form_id"
                        class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-primary focus:border-primary"
                        onchange="updatePreview()">
                        <option value="">Varsayılan Form (iletisim)</option>
                        <?php 
                        // Form model'i kullanarak formları çek
                        if (class_exists('Form')) {
                            try {
                                $formModel = new Form();
                                $forms = $formModel->getAll();
                                $selectedFormId = $contactSettings['form_id'] ?? '';
                                foreach ($forms as $form): 
                                    if (($form['status'] ?? 'active') === 'active'):
                                ?>
                                <option value="<?php echo $form['id']; ?>" <?php echo $selectedFormId == $form['id'] ? 'selected' : ''; ?>>
                                    <?php echo esc_html($form['name']); ?>
                                </option>
                                <?php 
                                    endif;
                                endforeach;
                            } catch (Exception $e) {
                                // Form model yoksa veritabanından çek
                                $db = Database::getInstance();
                                $forms = $db->fetchAll("SELECT id, name FROM forms WHERE status = 'active' ORDER BY name");
                                $selectedFormId = $contactSettings['form_id'] ?? '';
                                foreach ($forms as $form): 
                                ?>
                                <option value="<?php echo $form['id']; ?>" <?php echo $selectedFormId == $form['id'] ? 'selected' : ''; ?>>
                                    <?php echo esc_html($form['name']); ?>
                                </option>
                                <?php 
                                endforeach;
                            }
                        } else {
                            // Form model yoksa veritabanından çek
                            $db = Database::getInstance();
                            $forms = $db->fetchAll("SELECT id, name FROM forms WHERE status = 'active' ORDER BY name");
                            $selectedFormId = $contactSettings['form_id'] ?? '';
                            foreach ($forms as $form): 
                            ?>
                            <option value="<?php echo $form['id']; ?>" <?php echo $selectedFormId == $form['id'] ? 'selected' : ''; ?>>
                                <?php echo esc_html($form['name']); ?>
                            </option>
                            <?php 
                            endforeach;
                        }
                        ?>
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        İletişim sayfasındaki "Mesaj Gönderin" bölümünde gösterilecek formu seçin.
                    </p>
                </div>
                
                <!-- Harita Gösterimi -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" 
                            id="contact-show-map"
                            data-group="page_contact"
                            data-key="show_map"
                            class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                            <?php echo (!isset($contactSettings['show_map']) || $contactSettings['show_map'] === '1') ? 'checked' : ''; ?>
                            onchange="updatePreview()">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Haritayı Göster</span>
                    </label>
                </div>
                
                <!-- Sosyal Medya Gösterimi -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" 
                            id="contact-show-social"
                            data-group="page_contact"
                            data-key="show_social"
                            class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                            <?php echo (!isset($contactSettings['show_social']) || $contactSettings['show_social'] === '1') ? 'checked' : ''; ?>
                            onchange="updatePreview()">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Sosyal Medya İkonları</span>
                    </label>
                </div>
                
                <!-- Çalışma Saatleri Gösterimi -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" 
                            id="contact-show-hours"
                            data-group="page_contact"
                            data-key="show_hours"
                            class="setting-input w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                            <?php echo (!isset($contactSettings['show_hours']) || $contactSettings['show_hours'] === '1') ? 'checked' : ''; ?>
                            onchange="updatePreview()">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Çalışma Saatlerini Göster</span>
                    </label>
                </div>
                
                <!-- Hero Başlığı -->
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Sayfa Başlığı</label>
                    <input type="text"
                        id="contact-hero-title"
                        data-group="page_contact"
                        data-key="hero_title"
                        value="<?php echo esc_attr($contactSettings['hero_title'] ?? 'Bizimle İletişime Geçin'); ?>"
                        class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                        onchange="updatePreview()">
                </div>
                
                <!-- Hero Alt Başlık -->
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Açıklama</label>
                    <textarea
                        id="contact-hero-subtitle"
                        data-group="page_contact"
                        data-key="hero_subtitle"
                        rows="2"
                        class="setting-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 resize-none"
                        onchange="updatePreview()"><?php echo esc_html($contactSettings['hero_subtitle'] ?? 'Sorularınız, önerileriniz veya işbirliği teklifleriniz için bize ulaşabilirsiniz.'); ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Ana Sayfa Bölümleri -->
        <div class="customizer-section border-b border-gray-200 dark:border-gray-700" id="home-sections-section">
            <button type="button" class="section-toggle w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" onclick="toggleSection(this)">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">dashboard</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Ana Sayfa Bölümleri</span>
                </div>
                <span class="material-symbols-outlined text-gray-400 toggle-icon">expand_more</span>
            </button>
            <div class="section-content hidden px-4 pb-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Bölümleri sürükleyerek sıralayın, göz ikonuyla gizleyin:</p>
                <div id="home-sections-list" class="space-y-2">
                    <?php 
                    // Veritabanından section verilerini al
                    $dbSections = $themeManager->getPageSections('home') ?? [];
                    $dbSectionsMap = [];
                    foreach ($dbSections as $s) {
                        $dbSectionsMap[$s['section_id']] = $s;
                    }
                    
                    $homeSections = [
                        ['id' => 'hero', 'name' => 'Hero Bölümü', 'component' => 'hero'],
                        ['id' => 'features', 'name' => 'Özellikler', 'component' => 'features'],
                        ['id' => 'about', 'name' => 'Hakkımızda', 'component' => 'about'],
                        ['id' => 'services', 'name' => 'Hizmetler', 'component' => 'services'],
                        ['id' => 'testimonials', 'name' => 'Referanslar', 'component' => 'testimonials'],
                        ['id' => 'cta', 'name' => 'Call to Action', 'component' => 'cta'],
                    ];
                    
                    foreach ($homeSections as $section): 
                        $dbSection = $dbSectionsMap[$section['id']] ?? null;
                        $isEnabled = $dbSection ? (bool)$dbSection['is_active'] : true;
                        $dbId = $dbSection ? $dbSection['id'] : '';
                    ?>
                    <div class="home-section-item flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 cursor-move <?php echo !$isEnabled ? 'opacity-50' : ''; ?>" 
                         data-section-id="<?php echo $section['id']; ?>"
                         data-db-id="<?php echo $dbId; ?>"
                         data-component="<?php echo $section['component']; ?>">
                        <span class="material-symbols-outlined text-gray-400 drag-handle">drag_indicator</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo esc_html($section['name']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo esc_html($section['component']); ?>.php</p>
                        </div>
                        <button type="button" onclick="editHomeSection('<?php echo $section['id']; ?>', '<?php echo esc_attr($section['name']); ?>')" 
                            class="p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Düzenle">
                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-xl">edit</span>
                        </button>
                        <button type="button" onclick="toggleHomeSection('<?php echo $section['id']; ?>')" 
                            class="section-visibility-btn p-1.5 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors" 
                            data-enabled="<?php echo $isEnabled ? 'true' : 'false'; ?>">
                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-xl"><?php echo $isEnabled ? 'visibility' : 'visibility_off'; ?></span>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="flex items-center justify-between p-3 sm:p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <button type="button" onclick="resetToDefaults()" class="text-xs sm:text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 min-h-[36px]">
                Varsayılanlara Dön
            </button>
        </div>
    </aside>
    
    <!-- Preview Frame -->
    <div class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Mobile Sidebar Toggle -->
        <button onclick="toggleCustomizerSidebar()" class="lg:hidden fixed top-4 left-4 z-40 p-2 bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">menu</span>
        </button>
        
        <!-- Mobile Overlay -->
        <div id="customizer-overlay" onclick="toggleCustomizerSidebar()" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"></div>
        
        <!-- Cihaz Önizleme Toolbar -->
        <div class="p-2 sm:p-3 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex items-center justify-center gap-1 overflow-x-auto scrollbar-hide">
            <button type="button" onclick="changeDevice('desktop')" class="device-btn p-1.5 sm:p-2 rounded-lg bg-primary/10 text-primary transition-colors flex-shrink-0 min-h-[36px] min-w-[36px] flex items-center justify-center" data-device="desktop" title="Masaüstü">
                <span class="material-symbols-outlined text-lg sm:text-xl">computer</span>
            </button>
            <button type="button" onclick="changeDevice('tablet')" class="device-btn p-1.5 sm:p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex-shrink-0 min-h-[36px] min-w-[36px] flex items-center justify-center" data-device="tablet" title="Tablet">
                <span class="material-symbols-outlined text-lg sm:text-xl">tablet</span>
            </button>
            <button type="button" onclick="changeDevice('mobile')" class="device-btn p-1.5 sm:p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex-shrink-0 min-h-[36px] min-w-[36px] flex items-center justify-center" data-device="mobile" title="Mobil">
                <span class="material-symbols-outlined text-lg sm:text-xl">smartphone</span>
            </button>
            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1 sm:mx-2 flex-shrink-0"></div>
            <button type="button" onclick="refreshPreview()" class="p-1.5 sm:p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors flex-shrink-0 min-h-[36px] min-w-[36px] flex items-center justify-center" title="Yenile">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-lg sm:text-xl">refresh</span>
            </button>
        </div>
        <div id="preview-container" class="flex-1 flex items-start justify-center p-2 sm:p-4 lg:p-8 bg-gray-200 dark:bg-gray-950 overflow-auto">
            <div id="preview-frame-wrapper" class="w-full h-full bg-white rounded-lg shadow-2xl overflow-hidden transition-all duration-300">
                <iframe id="preview-frame" src="<?php echo admin_url('themes/preview/' . $theme['slug']); ?>" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Section Düzenleme Modal -->
<div id="section-edit-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSectionModal()"></div>
    <div class="absolute inset-4 md:inset-10 lg:inset-20 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl flex flex-col overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 id="section-modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Section Düzenle</h3>
            <button onclick="closeSectionModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <form id="section-edit-form" class="space-y-6">
                <input type="hidden" id="section-db-id" name="id">
                <input type="hidden" id="section-page-type" name="page_type">
                <input type="hidden" id="section-section-id" name="section_id">
                <input type="hidden" id="section-component" name="section_component">
                
                <!-- Başlık -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Başlık</label>
                    <input type="text" id="section-title" name="title" 
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                </div>
                
                <!-- Alt Başlık -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alt Başlık / Açıklama</label>
                    <textarea id="section-subtitle" name="subtitle" rows="2"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors resize-none"></textarea>
                </div>
                
                <!-- İçerik (Hero, CTA vb için) -->
                <div id="section-content-wrapper">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İçerik</label>
                    <textarea id="section-content" name="content" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors resize-none"></textarea>
                </div>
                
                <!-- Hero Arka Plan Ayarları -->
                <div id="section-hero-background-wrapper" class="hidden space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Arka Plan Ayarları</h4>
                    
                    <!-- Arka Plan Görseli -->
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-2">Arka Plan Görseli</label>
                        <div class="flex items-center gap-3">
                            <div id="hero-bg-preview" class="flex-shrink-0 w-32 h-20 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                <span class="material-symbols-outlined text-gray-400 text-2xl">image</span>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="hidden" id="section-background-image" name="settings[background_image]">
                                <div class="flex gap-2">
                                    <button type="button" onclick="openHeroBgPicker()" class="flex items-center gap-1 px-3 py-1.5 text-xs bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                        <span class="material-symbols-outlined text-base">perm_media</span>
                                        Görsel Seç
                                    </button>
                                    <button type="button" onclick="removeHeroBg()" class="px-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        Kaldır
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Overlay Ayarları -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Overlay Rengi</label>
                            <input type="color" id="section-overlay-color" name="settings[overlay_color]" value="#111827"
                                class="w-full h-10 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Overlay Opaklığı: <span id="overlay-opacity-value">80</span>%</label>
                            <input type="range" id="section-overlay-opacity" name="settings[overlay_opacity]" min="0" max="100" value="80"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                oninput="document.getElementById('overlay-opacity-value').textContent = this.value">
                        </div>
                    </div>
                </div>
                
                <!-- Ayarlar (Buton metni, link vb) -->
                <div id="section-settings-wrapper" class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Buton Ayarları</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Birincil Buton Metni</label>
                            <input type="text" id="section-button-text" name="settings[button_text]"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Birincil Buton Linki</label>
                            <input type="text" id="section-button-link" name="settings[button_link]"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                        </div>
                    </div>
                    <!-- İkincil Buton (Hero için) -->
                    <div id="section-secondary-button-wrapper" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">İkincil Buton Metni</label>
                            <input type="text" id="section-secondary-button-text" name="settings[secondary_button_text]"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">İkincil Buton Linki</label>
                            <input type="text" id="section-secondary-button-link" name="settings[secondary_button_link]"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                        </div>
                    </div>
                    
                    <!-- CTA Form Seçici -->
                    <div id="section-cta-form-wrapper" class="hidden space-y-3">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Form Ayarları</h4>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer mb-2">
                                <input type="checkbox" id="section-show-form" name="settings[show_form]"
                                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                                    onchange="toggleCtaFormDisplay()">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Form Göster</span>
                            </label>
                        </div>
                        <div id="section-form-select-wrapper" class="hidden">
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Form Seç</label>
                            <select id="section-form-id" name="settings[form_id]"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                                <option value="">Form Seçin...</option>
                                <?php foreach ($forms as $form): ?>
                                <option value="<?php echo esc_attr($form['id']); ?>">
                                    <?php echo esc_html($form['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Form seçildiğinde buton yerine form gösterilir.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Hero İstatistikler -->
                <div id="section-stats-wrapper" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 flex-1">İstatistikler</h4>
                        <button type="button" onclick="addStatItem()" class="text-sm text-primary hover:text-primary/80 font-medium">+ İstatistik Ekle</button>
                    </div>
                    <div id="section-stats-list" class="space-y-3"></div>
                </div>
                
                <!-- Hakkımızda Özel Alanlar -->
                <div id="section-about-wrapper" class="hidden space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Görsel ve Badge Ayarları</h4>
                    
                    <!-- Hakkımızda Görseli -->
                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-2">Hakkımızda Görseli</label>
                        <div class="flex items-center gap-3">
                            <div id="about-image-preview" class="flex-shrink-0 w-32 h-24 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                <span class="material-symbols-outlined text-gray-400 text-2xl">image</span>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="hidden" id="section-about-image" name="settings[image]">
                                <div class="flex gap-2">
                                    <button type="button" onclick="openAboutImagePicker()" class="flex items-center gap-1 px-3 py-1.5 text-xs bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                        <span class="material-symbols-outlined text-base">perm_media</span>
                                        Görsel Seç
                                    </button>
                                    <button type="button" onclick="removeAboutImage()" class="px-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        Kaldır
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Badge Ayarları -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Badge Değeri</label>
                            <input type="text" id="section-badge-value" name="settings[badge_value]" placeholder="10+"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Badge Etiketi</label>
                            <input type="text" id="section-badge-label" name="settings[badge_label]" placeholder="Yıllık Deneyim"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm">
                        </div>
                    </div>
                    
                    <!-- Özellik Listesi -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm text-gray-600 dark:text-gray-400">Özellik Listesi</label>
                            <button type="button" onclick="addAboutFeature()" class="text-sm text-primary hover:text-primary/80 font-medium">+ Özellik Ekle</button>
                        </div>
                        <div id="about-features-list" class="space-y-2"></div>
                    </div>
                </div>
                
                <!-- Items (Features, Testimonials vb için) -->
                <div id="section-items-wrapper" class="hidden">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Öğeler</h4>
                        <button type="button" onclick="addSectionItem()" class="text-sm text-primary hover:text-primary/80 font-medium">+ Öğe Ekle</button>
                    </div>
                    <div id="section-items-list" class="space-y-3"></div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <button onclick="closeSectionModal()" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">İptal</button>
            <button onclick="saveSectionData()" class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-colors">Kaydet</button>
        </div>
    </div>
</div>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
const themeSlug = '<?php echo esc_js($theme['slug']); ?>';
let hasChanges = false;

// Section Toggle
function toggleSection(btn) {
    const section = btn.closest('.customizer-section');
    const content = section.querySelector('.section-content');
    const icon = btn.querySelector('.toggle-icon');
    
    content.classList.toggle('hidden');
    icon.textContent = content.classList.contains('hidden') ? 'expand_more' : 'expand_less';
}

// Copyright Format Toggle
function toggleCopyrightFormat(format) {
    const defaultFormat = document.getElementById('copyright-default-format');
    const customFormat = document.getElementById('copyright-custom-format');
    
    if (format === 'custom') {
        if (defaultFormat) defaultFormat.style.display = 'none';
        if (customFormat) customFormat.style.display = 'block';
    } else {
        if (defaultFormat) defaultFormat.style.display = 'block';
        if (customFormat) customFormat.style.display = 'none';
    }
}

// Copyright Year Toggle
function toggleCopyrightYear(yearType) {
    const manualYear = document.getElementById('copyright-manual-year');
    
    if (manualYear) {
        manualYear.style.display = (yearType === 'manual') ? 'block' : 'none';
    }
}

// Collect Settings
function collectSettings() {
    const settings = {};
    
    document.querySelectorAll('.setting-input').forEach(input => {
        const group = input.dataset.group;
        const key = input.dataset.key;
        
        if (!group || !key) return;
        
        if (!settings[group]) settings[group] = {};
        
        if (input.type === 'checkbox') {
            settings[group][key] = input.checked ? '1' : '0';
        } else if (input.tagName === 'TEXTAREA') {
            settings[group][key] = input.value;
        } else {
            settings[group][key] = input.value;
        }
    });
    
    // Add home sections order
    settings.home_sections = collectHomeSections();
    
    // Bekleyen section değişikliklerini ekle (önizleme için)
    if (Object.keys(window.pendingSections).length > 0) {
        settings.pending_sections = window.pendingSections;
    }
    
    return settings;
}

// Update Preview
function updatePreview() {
    hasChanges = true;
    document.getElementById('save-settings').disabled = false;
    
    const settings = collectSettings();
    const iframe = document.getElementById('preview-frame');
    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    
    // CSS değişkenlerini güncelle
    if (settings.colors) {
        const root = iframeDoc.documentElement;
        for (const [key, value] of Object.entries(settings.colors)) {
            root.style.setProperty('--color-' + key, value);
            
            // RGB versiyonunu da güncelle
            const rgb = hexToRgb(value);
            if (rgb) {
                root.style.setProperty('--color-' + key + '-rgb', rgb.r + ', ' + rgb.g + ', ' + rgb.b);
            }
        }
    }
    
    // Font değişkenlerini güncelle
    if (settings.fonts) {
        const root = iframeDoc.documentElement;
        for (const [key, value] of Object.entries(settings.fonts)) {
            root.style.setProperty('--font-' + key, "'" + value + "', sans-serif");
        }
    }
    
    // Footer renk ayarlarını güncelle
    if (settings.custom) {
        const footer = iframeDoc.querySelector('footer');
        if (footer) {
            // Footer arka plan rengi
            if (settings.custom.footer_bg_color) {
                footer.style.backgroundColor = settings.custom.footer_bg_color;
            }
            
            // Footer metin rengi
            if (settings.custom.footer_text_color) {
                footer.style.color = settings.custom.footer_text_color;
                
                // Footer içindeki muted text renklerini de güncelle
                const textColor = hexToRgb(settings.custom.footer_text_color);
                if (textColor) {
                    const mutedRgba = `rgba(${textColor.r}, ${textColor.g}, ${textColor.b}, 0.6)`;
                    const borderRgba = `rgba(${textColor.r}, ${textColor.g}, ${textColor.b}, 0.1)`;
                    const iconBgRgba = `rgba(${textColor.r}, ${textColor.g}, ${textColor.b}, 0.08)`;
                    
                    // Tüm inline style'lı elementleri güncelle
                    footer.querySelectorAll('*').forEach(el => {
                        const style = el.getAttribute('style') || '';
                        if (style) {
                            let newStyle = style;
                            
                            // Muted text renklerini güncelle (rgba ile tanımlı renkler)
                            if (style.includes('color:') && style.includes('rgba')) {
                                const colorMatch = style.match(/color:\s*rgba\([^)]+\)/g);
                                if (colorMatch) {
                                    colorMatch.forEach(match => {
                                        // Muted color (0.6 opacity) olanları güncelle
                                        if (match.includes('0.6') || match.includes('0, 0, 0, 0.6')) {
                                            newStyle = newStyle.replace(match, `color: ${mutedRgba}`);
                                        }
                                    });
                                }
                            }
                            
                            // Border renklerini güncelle
                            if (style.includes('border-color') && style.includes('rgba')) {
                                const borderMatch = style.match(/border-color:\s*rgba\([^)]+\)/g);
                                if (borderMatch) {
                                    borderMatch.forEach(match => {
                                        if (match.includes('0.1') || match.includes('0, 0, 0, 0.1')) {
                                            newStyle = newStyle.replace(match, `border-color: ${borderRgba}`);
                                        }
                                    });
                                }
                            }
                            
                            // Icon background renklerini güncelle
                            if (style.includes('background-color') && style.includes('rgba')) {
                                const bgMatch = style.match(/background-color:\s*rgba\([^)]+\)/g);
                                if (bgMatch) {
                                    bgMatch.forEach(match => {
                                        if (match.includes('0.08') || match.includes('0, 0, 0, 0.08')) {
                                            newStyle = newStyle.replace(match, `background-color: ${iconBgRgba}`);
                                        }
                                    });
                                }
                            }
                            
                            if (newStyle !== style) {
                                el.setAttribute('style', newStyle);
                            }
                        }
                    });
                }
            }
        }
    }
}

// Hex to RGB
function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

// Save Settings (Yayınla butonu)
document.getElementById('save-settings').addEventListener('click', async () => {
    const btn = document.getElementById('save-settings');
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin">⟳</span> Yayınlanıyor...';
    
    try {
        // Önce bekleyen section değişikliklerini kaydet
        const hasPendingSections = Object.keys(window.pendingSections).length > 0;
        if (hasPendingSections) {
            const sectionsPublished = await publishPendingSections();
            if (!sectionsPublished) {
                throw new Error('Section değişiklikleri kaydedilemedi');
            }
        }
        
        // Sonra diğer ayarları kaydet (pending_sections olmadan)
        const settingsToSave = collectSettings();
        delete settingsToSave.pending_sections; // Bu sadece önizleme için
        
        const response = await fetch('<?php echo admin_url('themes/saveSettings'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                theme_slug: themeSlug,
                settings: settingsToSave
            })
        });
        
        // Oturum kontrolü
        if (response.status === 401) {
            showNotification('Oturum süresi dolmuş. Sayfa yenileniyor...', 'error');
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            hasChanges = false;
            window.pendingSections = {}; // Bekleyen değişiklikleri temizle
            btn.innerHTML = '✓ Yayınlandı';
            showNotification('Tüm değişiklikler yayınlandı!', 'success');
            setTimeout(() => {
                btn.innerHTML = 'Yayınla';
                btn.disabled = true;
            }, 2000);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        showNotification('Hata: ' + error.message, 'error');
        btn.innerHTML = 'Yayınla';
        btn.disabled = false;
    }
});

// Save Custom Code
async function saveCustomCode(type) {
    const content = document.getElementById('custom-' + type).value;
    
    try {
        const formData = new FormData();
        formData.append('type', type);
        formData.append('content', content);
        
        const response = await fetch('<?php echo admin_url('themes/saveCustomCode'); ?>', {
            method: 'POST',
            body: formData
        });
        
        // Oturum kontrolü
        if (response.status === 401) {
            showNotification('Oturum süresi dolmuş. Sayfa yenileniyor...', 'error');
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            refreshPreview();
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        alert('Hata: ' + error.message);
    }
}

// Device Change
function changeDevice(device) {
    const wrapper = document.getElementById('preview-frame-wrapper');
    
    // Update device buttons
    document.querySelectorAll('.device-btn').forEach(btn => {
        btn.classList.remove('bg-primary/10', 'text-primary');
        btn.classList.add('text-gray-500', 'dark:text-gray-400');
    });
    const activeBtn = document.querySelector(`[data-device="${device}"]`);
    if (activeBtn) {
        activeBtn.classList.add('bg-primary/10', 'text-primary');
        activeBtn.classList.remove('text-gray-500', 'dark:text-gray-400');
    }
    
    wrapper.classList.remove('w-full', 'max-w-md', 'max-w-3xl');
    
    switch(device) {
        case 'mobile':
            wrapper.classList.add('max-w-md');
            wrapper.style.width = '375px';
            wrapper.style.height = '667px';
            break;
        case 'tablet':
            wrapper.classList.add('max-w-3xl');
            wrapper.style.width = '768px';
            wrapper.style.height = '1024px';
            break;
        default:
            wrapper.classList.add('w-full');
            wrapper.style.width = '100%';
            wrapper.style.height = '100%';
    }
}

// Toggle Home Section Visibility
function toggleHomeSection(sectionId) {
    const btn = document.querySelector(`[data-section-id="${sectionId}"] .section-visibility-btn`);
    const icon = btn.querySelector('.material-symbols-outlined');
    const isEnabled = btn.dataset.enabled === 'true';
    
    if (isEnabled) {
        btn.dataset.enabled = 'false';
        icon.textContent = 'visibility_off';
        icon.classList.add('text-gray-400');
        btn.closest('.home-section-item').classList.add('opacity-50');
    } else {
        btn.dataset.enabled = 'true';
        icon.textContent = 'visibility';
        icon.classList.remove('text-gray-400');
        btn.closest('.home-section-item').classList.remove('opacity-50');
    }
    
    hasChanges = true;
    updatePreview();
}

// Initialize Home Sections Sortable
document.addEventListener('DOMContentLoaded', function() {
    const sectionsList = document.getElementById('home-sections-list');
    if (sectionsList && typeof Sortable !== 'undefined') {
        new Sortable(sectionsList, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'opacity-50',
            onEnd: function() {
                hasChanges = true;
                updatePreview();
            }
        });
    }
});

// Collect Home Sections Order
function collectHomeSections() {
    const sections = [];
    document.querySelectorAll('.home-section-item').forEach((item, index) => {
        sections.push({
            id: item.dataset.sectionId,
            order: index,
            enabled: item.querySelector('.section-visibility-btn').dataset.enabled === 'true'
        });
    });
    return sections;
}

// Preview Page Change
function changePreviewPage(page) {
    document.querySelectorAll('.preview-page-btn').forEach(btn => {
        btn.classList.remove('bg-primary/10', 'text-primary');
        btn.classList.add('text-gray-600', 'dark:text-gray-400');
    });
    
    const activeBtn = document.querySelector(`[data-page="${page}"]`);
    activeBtn.classList.add('bg-primary/10', 'text-primary');
    activeBtn.classList.remove('text-gray-600', 'dark:text-gray-400');
    
    // UTF-8 destekli base64 encoding (Türkçe karakterler için)
    const settingsJson = JSON.stringify(collectSettings());
    const settings = btoa(unescape(encodeURIComponent(settingsJson)));
    document.getElementById('preview-frame').src = '<?php echo admin_url('themes/preview/' . $theme['slug']); ?>&preview_page=' + page + '&settings=' + encodeURIComponent(settings);
}

// Refresh Preview
function refreshPreview() {
    // UTF-8 destekli base64 encoding (Türkçe karakterler için)
    const settingsJson = JSON.stringify(collectSettings());
    const settings = btoa(unescape(encodeURIComponent(settingsJson)));
    const currentPage = document.querySelector('.preview-page-btn.bg-primary\\/10')?.dataset.page || 'home';
    document.getElementById('preview-frame').src = '<?php echo admin_url('themes/preview/' . $theme['slug']); ?>&preview_page=' + currentPage + '&settings=' + encodeURIComponent(settings);
}

// Reset to Defaults
function resetToDefaults() {
    if (!confirm('Tüm özelleştirmeler varsayılan değerlere döndürülecek. Emin misiniz?')) return;
    
    document.querySelectorAll('.setting-input').forEach(input => {
        if (input.dataset.default) {
            if (input.type === 'checkbox') {
                input.checked = input.dataset.default === '1' || input.dataset.default === 'true';
            } else {
                input.value = input.dataset.default;
            }
        }
    });
    
    updatePreview();
}

// Color input sync
document.querySelectorAll('input[type="color"]').forEach(colorInput => {
    const textInput = colorInput.nextElementSibling;
    if (textInput && textInput.type === 'text') {
        colorInput.addEventListener('input', () => {
            textInput.value = colorInput.value;
            updatePreview();
        });
    }
});

// Warn on leave if unsaved changes
window.addEventListener('beforeunload', (e) => {
    if (hasChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// ==========================================
// SECTION DÜZENLEME FONKSİYONLARI
// ==========================================

let currentEditingSection = null;

// Varsayılan section verileri
const defaultSections = {
    hero: {
        title: 'Modern & Minimal Tasarım',
        subtitle: 'Web sitenizi profesyonel bir görünüme kavuşturun. Starter Theme ile kolayca özelleştirin ve yönetin.',
        content: '',
        settings: {
            button_text: 'Hemen Başla',
            button_link: '/contact',
            secondary_button_text: 'Daha Fazla',
            secondary_button_link: '#features',
            background_image: '',
            overlay_color: '#111827',
            overlay_opacity: '80'
        },
        items: [
            {value: '500+', label: 'Mutlu Müşteri'},
            {value: '50+', label: 'Tamamlanan Proje'},
            {value: '10+', label: 'Yıllık Deneyim'}
        ]
    },
    features: {
        title: 'Neden Bizi Tercih Etmelisiniz?',
        subtitle: 'Müşterilerimize en iyi deneyimi sunmak için çalışıyoruz.',
        content: '',
        settings: {},
        items: [
            {icon: 'rocket_launch', title: 'Hızlı Performans', description: 'Optimize edilmiş kod yapısı ile yüksek performans.'},
            {icon: 'palette', title: 'Modern Tasarım', description: 'Güncel trendlere uygun şık ve modern görünüm.'},
            {icon: 'devices', title: 'Responsive', description: 'Tüm cihazlarda mükemmel görünüm.'},
            {icon: 'security', title: 'Güvenli', description: 'En güncel güvenlik standartları.'},
            {icon: 'support_agent', title: '7/24 Destek', description: 'Her zaman yanınızda olan destek ekibi.'},
            {icon: 'settings', title: 'Kolay Yönetim', description: 'Kullanıcı dostu admin paneli.'}
        ]
    },
    about: {
        title: 'Deneyim ve Kalite',
        subtitle: 'Biz kimiz ve ne yapıyoruz?',
        content: 'Yılların deneyimi ile müşterilerimize en kaliteli hizmeti sunuyoruz. Profesyonel ekibimiz ile projelerinizi hayata geçiriyoruz. Müşteri memnuniyeti bizim için en önemli önceliktir.',
        settings: {
            image: '',
            badge_value: '10+',
            badge_label: 'Yıllık Deneyim',
            button_text: 'Daha Fazla Bilgi',
            button_link: '/about'
        },
        items: [
            {text: 'Profesyonel Ekip'},
            {text: 'Müşteri Odaklı Yaklaşım'},
            {text: 'Kaliteli ve Hızlı Hizmet'}
        ]
    },
    testimonials: {
        title: 'Müşterilerimiz Ne Diyor?',
        subtitle: 'Birlikte çalıştığımız müşterilerimizden geri bildirimler.',
        content: '',
        settings: {},
        items: [
            {name: 'Ahmet Yılmaz', role: 'CEO, TechCorp', content: 'Harika bir deneyimdi. Profesyonel yaklaşımları ve kaliteli işleri ile beklentilerimizi aştılar.', rating: 5},
            {name: 'Elif Demir', role: 'Marketing Manager', content: 'Projemiz zamanında ve bütçe dahilinde tamamlandı. Kesinlikle tekrar çalışmak isteriz.', rating: 5},
            {name: 'Mehmet Kara', role: 'Founder, StartupX', content: 'İletişimleri çok güçlü. Her adımda bilgilendirildik ve sonuç mükemmel oldu.', rating: 5}
        ]
    },
    cta: {
        title: 'Projenizi Hayata Geçirelim',
        subtitle: 'Hemen iletişime geçin ve size özel çözümlerimizi keşfedin.',
        content: '',
        settings: {
            button_text: 'Bize Ulaşın',
            button_link: '/contact',
            show_form: false,
            form_id: null
        },
        items: []
    },
    services: {
        title: 'Hizmetlerimiz',
        subtitle: 'Size özel çözümler sunuyoruz.',
        content: '',
        settings: {},
        items: []
    }
};

// Section düzenleme modalını aç
async function editHomeSection(sectionId, sectionName) {
    const modal = document.getElementById('section-edit-modal');
    const modalTitle = document.getElementById('section-modal-title');
    const sectionItem = document.querySelector(`[data-section-id="${sectionId}"]`);
    
    modalTitle.textContent = sectionName + ' Düzenle';
    currentEditingSection = sectionId;
    
    // Varsayılan değerleri yükle
    const defaults = defaultSections[sectionId] || {title: '', subtitle: '', content: '', settings: {}, items: []};
    
    // Form alanlarını varsayılanlarla doldur
    document.getElementById('section-title').value = defaults.title;
    document.getElementById('section-subtitle').value = defaults.subtitle;
    document.getElementById('section-content').value = defaults.content;
    document.getElementById('section-button-text').value = defaults.settings?.button_text || '';
    document.getElementById('section-button-link').value = defaults.settings?.button_link || '';
    document.getElementById('section-db-id').value = sectionItem?.dataset.dbId || '';
    document.getElementById('section-page-type').value = 'home';
    document.getElementById('section-section-id').value = sectionId;
    document.getElementById('section-component').value = sectionItem?.dataset.component || sectionId;
    
    // Hero için ekstra alanlar
    document.getElementById('section-secondary-button-text').value = defaults.settings?.secondary_button_text || '';
    document.getElementById('section-secondary-button-link').value = defaults.settings?.secondary_button_link || '';
    document.getElementById('section-background-image').value = defaults.settings?.background_image || '';
    document.getElementById('section-overlay-color').value = defaults.settings?.overlay_color || '#111827';
    document.getElementById('section-overlay-opacity').value = defaults.settings?.overlay_opacity || '80';
    document.getElementById('overlay-opacity-value').textContent = defaults.settings?.overlay_opacity || '80';
    
    // Hero arka plan önizlemesi
    updateHeroBgPreview(defaults.settings?.background_image || '');
    
    // Hero için istatistikler
    if (sectionId === 'hero' && defaults.items && defaults.items.length > 0) {
        renderStatsItems(defaults.items);
    }
    
    // About için özel alanlar
    if (sectionId === 'about') {
        document.getElementById('section-about-image').value = defaults.settings?.image || '';
        document.getElementById('section-badge-value').value = defaults.settings?.badge_value || '10+';
        document.getElementById('section-badge-label').value = defaults.settings?.badge_label || 'Yıllık Deneyim';
        updateAboutImagePreview(defaults.settings?.image || '');
        renderAboutFeatures(defaults.items || []);
    }
    
    // CTA için form ayarları
    if (sectionId === 'cta') {
        document.getElementById('section-show-form').checked = defaults.settings?.show_form || false;
        document.getElementById('section-form-id').value = defaults.settings?.form_id || '';
        toggleCtaFormDisplay();
    }
    
    // Items varsa göster (features, testimonials için)
    if (defaults.items && defaults.items.length > 0 && sectionId !== 'hero' && sectionId !== 'about') {
        document.getElementById('section-items-wrapper').classList.remove('hidden');
        renderSectionItems(defaults.items);
    } else {
        document.getElementById('section-items-wrapper').classList.add('hidden');
    }
    
    // Önce bekleyen değişikliklerde (pendingSections) bu section var mı kontrol et
    const pendingKey = 'home_' + sectionId;
    
    if (window.pendingSections[pendingKey]) {
        const pending = window.pendingSections[pendingKey];
        document.getElementById('section-db-id').value = pending.id || '';
        document.getElementById('section-title').value = pending.title || defaults.title;
        document.getElementById('section-subtitle').value = pending.subtitle || defaults.subtitle;
        document.getElementById('section-content').value = pending.content || defaults.content;
        document.getElementById('section-button-text').value = pending.settings?.button_text || defaults.settings?.button_text || '';
        document.getElementById('section-button-link').value = pending.settings?.button_link || defaults.settings?.button_link || '';
        
        // Hero için ekstra alanlar
        document.getElementById('section-secondary-button-text').value = pending.settings?.secondary_button_text || defaults.settings?.secondary_button_text || '';
        document.getElementById('section-secondary-button-link').value = pending.settings?.secondary_button_link || defaults.settings?.secondary_button_link || '';
        document.getElementById('section-background-image').value = pending.settings?.background_image || defaults.settings?.background_image || '';
        document.getElementById('section-overlay-color').value = pending.settings?.overlay_color || defaults.settings?.overlay_color || '#111827';
        document.getElementById('section-overlay-opacity').value = pending.settings?.overlay_opacity || defaults.settings?.overlay_opacity || '80';
        document.getElementById('overlay-opacity-value').textContent = pending.settings?.overlay_opacity || defaults.settings?.overlay_opacity || '80';
        updateHeroBgPreview(pending.settings?.background_image || defaults.settings?.background_image || '');
        
        // Hero için istatistikler
        if (sectionId === 'hero') {
            const stats = pending.items && pending.items.length > 0 ? pending.items : defaults.items;
            renderStatsItems(stats);
        }
        
        // About için özel alanlar
        if (sectionId === 'about') {
            document.getElementById('section-about-image').value = pending.settings?.image || defaults.settings?.image || '';
            document.getElementById('section-badge-value').value = pending.settings?.badge_value || defaults.settings?.badge_value || '10+';
            document.getElementById('section-badge-label').value = pending.settings?.badge_label || defaults.settings?.badge_label || 'Yıllık Deneyim';
            updateAboutImagePreview(pending.settings?.image || defaults.settings?.image || '');
            const features = pending.items && pending.items.length > 0 ? pending.items : defaults.items;
            renderAboutFeatures(features || []);
        }
        
        // CTA için form ayarları
        if (sectionId === 'cta') {
            document.getElementById('section-show-form').checked = pending.settings?.show_form || defaults.settings?.show_form || false;
            document.getElementById('section-form-id').value = pending.settings?.form_id || defaults.settings?.form_id || '';
            toggleCtaFormDisplay();
        }
        
        if (pending.items && pending.items.length > 0 && sectionId !== 'hero' && sectionId !== 'about') {
            document.getElementById('section-items-wrapper').classList.remove('hidden');
            renderSectionItems(pending.items);
        }
        
        // Section tipine göre alanları göster/gizle
        updateModalFields(sectionId);
        modal.classList.remove('hidden');
        return; // Bekleyen değişiklik varsa veritabanından çekmeye gerek yok
    }
    
    // Veritabanından section verisini çek (varsa üzerine yaz)
    try {
        const response = await fetch('<?php echo admin_url('themes/getSectionData'); ?>&page_type=home&section_id=' + sectionId);
        
        // Oturum kontrolü
        if (response.status === 401) {
            showNotification('Oturum süresi dolmuş. Sayfa yenileniyor...', 'error');
            setTimeout(() => window.location.reload(), 2000);
            return;
        }
        
        const responseText = await response.text();
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON parse hatası. Response:', responseText);
            // Parse hatası varsa varsayılan değerlerle devam et
            data = { success: false };
        }
        
        if (data.success && data.section) {
            const section = data.section;
            document.getElementById('section-db-id').value = section.id || '';
            
            // Boş değilse üzerine yaz, boşsa varsayılanı koru
            if (section.title) {
                document.getElementById('section-title').value = section.title;
            }
            if (section.subtitle) {
                document.getElementById('section-subtitle').value = section.subtitle;
            }
            if (section.content) {
                document.getElementById('section-content').value = section.content;
            }
            
            if (section.settings) {
                if (section.settings.button_text) {
                    document.getElementById('section-button-text').value = section.settings.button_text;
                }
                if (section.settings.button_link) {
                    document.getElementById('section-button-link').value = section.settings.button_link;
                }
                // Hero için ekstra ayarlar
                if (section.settings.secondary_button_text) {
                    document.getElementById('section-secondary-button-text').value = section.settings.secondary_button_text;
                }
                if (section.settings.secondary_button_link) {
                    document.getElementById('section-secondary-button-link').value = section.settings.secondary_button_link;
                }
                if (section.settings.background_image !== undefined) {
                    document.getElementById('section-background-image').value = section.settings.background_image;
                    updateHeroBgPreview(section.settings.background_image);
                }
                if (section.settings.overlay_color) {
                    document.getElementById('section-overlay-color').value = section.settings.overlay_color;
                }
                if (section.settings.overlay_opacity) {
                    document.getElementById('section-overlay-opacity').value = section.settings.overlay_opacity;
                    document.getElementById('overlay-opacity-value').textContent = section.settings.overlay_opacity;
                }
            }
            
            // Hero için istatistikler
            if (sectionId === 'hero' && section.items && section.items.length > 0) {
                renderStatsItems(section.items);
            }
            
            // About için özel alanlar
            if (sectionId === 'about') {
                if (section.settings?.image !== undefined) {
                    document.getElementById('section-about-image').value = section.settings.image;
                    updateAboutImagePreview(section.settings.image);
                }
                if (section.settings?.badge_value) {
                    document.getElementById('section-badge-value').value = section.settings.badge_value;
                }
                if (section.settings?.badge_label) {
                    document.getElementById('section-badge-label').value = section.settings.badge_label;
                }
                if (section.items && section.items.length > 0) {
                    renderAboutFeatures(section.items);
                }
            }
            
            // CTA için form ayarları
            if (sectionId === 'cta') {
                if (section.settings?.show_form !== undefined) {
                    document.getElementById('section-show-form').checked = section.settings.show_form;
                }
                if (section.settings?.form_id) {
                    document.getElementById('section-form-id').value = section.settings.form_id;
                }
                toggleCtaFormDisplay();
            }
            
            // Items varsa göster (veritabanından gelen items öncelikli) - hero ve about hariç
            if (section.items && section.items.length > 0 && sectionId !== 'hero' && sectionId !== 'about') {
                document.getElementById('section-items-wrapper').classList.remove('hidden');
                renderSectionItems(section.items);
            }
        }
    } catch (error) {
        // Varsayılan değerler kullanılacak
    }
    
    // Section tipine göre alanları göster/gizle
    updateModalFields(sectionId);
    
    modal.classList.remove('hidden');
}

// Modal alanlarını section tipine göre güncelle
function updateModalFields(sectionId) {
    const contentWrapper = document.getElementById('section-content-wrapper');
    const settingsWrapper = document.getElementById('section-settings-wrapper');
    const itemsWrapper = document.getElementById('section-items-wrapper');
    const heroBackgroundWrapper = document.getElementById('section-hero-background-wrapper');
    const secondaryButtonWrapper = document.getElementById('section-secondary-button-wrapper');
    const statsWrapper = document.getElementById('section-stats-wrapper');
    const aboutWrapper = document.getElementById('section-about-wrapper');
    const ctaFormWrapper = document.getElementById('section-cta-form-wrapper');
    
    // Varsayılan: hepsini gizle/göster
    contentWrapper.classList.remove('hidden');
    settingsWrapper.classList.remove('hidden');
    itemsWrapper.classList.add('hidden');
    heroBackgroundWrapper.classList.add('hidden');
    secondaryButtonWrapper.classList.add('hidden');
    statsWrapper.classList.add('hidden');
    aboutWrapper.classList.add('hidden');
    ctaFormWrapper.classList.add('hidden');
    
    // Section tipine göre özelleştir
    switch(sectionId) {
        case 'hero':
            heroBackgroundWrapper.classList.remove('hidden');
            secondaryButtonWrapper.classList.remove('hidden');
            statsWrapper.classList.remove('hidden');
            contentWrapper.classList.add('hidden');
            itemsWrapper.classList.add('hidden');
            break;
        case 'cta':
            ctaFormWrapper.classList.remove('hidden');
            itemsWrapper.classList.add('hidden');
            break;
        case 'features':
        case 'testimonials':
            contentWrapper.classList.add('hidden');
            settingsWrapper.classList.add('hidden');
            itemsWrapper.classList.remove('hidden');
            break;
        case 'about':
            aboutWrapper.classList.remove('hidden');
            settingsWrapper.classList.remove('hidden');
            itemsWrapper.classList.add('hidden');
            break;
    }
}

// Section items render
// Popüler Material Icons listesi
const popularIcons = [
    'rocket_launch', 'palette', 'devices', 'security', 'support_agent', 'settings',
    'speed', 'trending_up', 'verified', 'star', 'favorite', 'bolt',
    'cloud', 'code', 'design_services', 'insights', 'lightbulb', 'psychology',
    'auto_awesome', 'diamond', 'eco', 'fingerprint', 'grid_view', 'hub',
    'integration_instructions', 'layers', 'memory', 'monitor_heart', 'paid', 'payments',
    'precision_manufacturing', 'query_stats', 'rocket', 'savings', 'schedule', 'science',
    'sell', 'shopping_cart', 'storefront', 'terminal', 'token', 'visibility',
    'work', 'apartment', 'business', 'camera', 'chat', 'construction',
    'dashboard', 'edit', 'email', 'extension', 'folder', 'group',
    'handshake', 'home', 'inventory', 'language', 'local_shipping', 'lock'
];

function renderSectionItems(items) {
    const container = document.getElementById('section-items-list');
    container.innerHTML = '';
    
    items.forEach((item, index) => {
        const iconValue = item.icon || 'star';
        const itemHtml = `
            <div class="section-item flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg" data-index="${index}">
                <span class="material-symbols-outlined text-gray-400 cursor-move mt-2">drag_indicator</span>
                <div class="flex-1 space-y-3">
                    <div class="flex items-center gap-3">
                        <!-- Icon Preview & Selector -->
                        <div class="relative">
                            <button type="button" onclick="toggleIconPicker(this)" class="icon-preview w-12 h-12 flex items-center justify-center bg-primary/10 text-primary rounded-xl hover:bg-primary/20 transition-colors">
                                <span class="material-symbols-outlined text-2xl">${iconValue}</span>
                            </button>
                            <input type="hidden" class="item-icon" value="${iconValue}">
                            <div class="icon-picker hidden absolute top-full left-0 mt-2 p-3 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 w-72">
                                <div class="mb-2">
                                    <input type="text" placeholder="İkon ara..." class="icon-search w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" oninput="filterIcons(this)">
                                </div>
                                <div class="icon-grid grid grid-cols-6 gap-1 max-h-48 overflow-y-auto">
                                    ${popularIcons.map(icon => `
                                        <button type="button" onclick="selectIcon(this, '${icon}')" class="icon-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors ${icon === iconValue ? 'bg-primary/10 text-primary' : ''}" data-icon="${icon}">
                                            <span class="material-symbols-outlined text-xl">${icon}</span>
                                        </button>
                                    `).join('')}
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <input type="text" placeholder="Özel ikon adı..." class="custom-icon-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" value="${iconValue}" onchange="selectCustomIcon(this)">
                                </div>
                            </div>
                        </div>
                        <input type="text" value="${item.title || ''}" placeholder="Başlık"
                            class="item-title flex-1 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <textarea placeholder="Açıklama" rows="2"
                        class="item-desc w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none">${item.description || item.content || ''}</textarea>
                </div>
                <button type="button" onclick="removeSectionItem(${index})" class="p-1.5 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg text-red-500 transition-colors">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', itemHtml);
    });
}

// Yeni item ekle
function addSectionItem() {
    const container = document.getElementById('section-items-list');
    const index = container.children.length;
    const defaultIcon = 'star';
    
    const itemHtml = `
        <div class="section-item flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg" data-index="${index}">
            <span class="material-symbols-outlined text-gray-400 cursor-move mt-2">drag_indicator</span>
            <div class="flex-1 space-y-3">
                <div class="flex items-center gap-3">
                    <!-- Icon Preview & Selector -->
                    <div class="relative">
                        <button type="button" onclick="toggleIconPicker(this)" class="icon-preview w-12 h-12 flex items-center justify-center bg-primary/10 text-primary rounded-xl hover:bg-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-2xl">${defaultIcon}</span>
                        </button>
                        <input type="hidden" class="item-icon" value="${defaultIcon}">
                        <div class="icon-picker hidden absolute top-full left-0 mt-2 p-3 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 w-72">
                            <div class="mb-2">
                                <input type="text" placeholder="İkon ara..." class="icon-search w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" oninput="filterIcons(this)">
                            </div>
                            <div class="icon-grid grid grid-cols-6 gap-1 max-h-48 overflow-y-auto">
                                ${popularIcons.map(icon => `
                                    <button type="button" onclick="selectIcon(this, '${icon}')" class="icon-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" data-icon="${icon}">
                                        <span class="material-symbols-outlined text-xl">${icon}</span>
                                    </button>
                                `).join('')}
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <input type="text" placeholder="Özel ikon adı..." class="custom-icon-input w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" value="${defaultIcon}" onchange="selectCustomIcon(this)">
                            </div>
                        </div>
                    </div>
                    <input type="text" value="" placeholder="Başlık"
                        class="item-title flex-1 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <textarea placeholder="Açıklama" rows="2"
                    class="item-desc w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"></textarea>
            </div>
            <button type="button" onclick="removeSectionItem(${index})" class="p-1.5 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg text-red-500 transition-colors">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
}

// Icon Picker fonksiyonları
function toggleIconPicker(btn) {
    // Diğer açık picker'ları kapat
    document.querySelectorAll('.icon-picker').forEach(p => {
        if (p !== btn.parentElement.querySelector('.icon-picker')) {
            p.classList.add('hidden');
        }
    });
    
    const picker = btn.parentElement.querySelector('.icon-picker');
    picker.classList.toggle('hidden');
}

function selectIcon(btn, iconName) {
    const container = btn.closest('.relative');
    const preview = container.querySelector('.icon-preview span');
    const input = container.querySelector('.item-icon');
    const customInput = container.querySelector('.custom-icon-input');
    
    // Güncelle
    preview.textContent = iconName;
    input.value = iconName;
    if (customInput) customInput.value = iconName;
    
    // Seçimi göster
    container.querySelectorAll('.icon-option').forEach(opt => {
        opt.classList.remove('bg-primary/10', 'text-primary');
    });
    btn.classList.add('bg-primary/10', 'text-primary');
    
    // Picker'ı kapat
    container.querySelector('.icon-picker').classList.add('hidden');
}

function selectCustomIcon(input) {
    const container = input.closest('.relative');
    const preview = container.querySelector('.icon-preview span');
    const hiddenInput = container.querySelector('.item-icon');
    
    const iconName = input.value.trim() || 'star';
    preview.textContent = iconName;
    hiddenInput.value = iconName;
}

function filterIcons(input) {
    const searchTerm = input.value.toLowerCase();
    const picker = input.closest('.icon-picker');
    const options = picker.querySelectorAll('.icon-option');
    
    options.forEach(opt => {
        const iconName = opt.dataset.icon;
        if (iconName.includes(searchTerm)) {
            opt.classList.remove('hidden');
        } else {
            opt.classList.add('hidden');
        }
    });
}

// Sayfa tıklamasında picker'ları kapat
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative') || !e.target.closest('.icon-preview')) {
        document.querySelectorAll('.icon-picker').forEach(p => p.classList.add('hidden'));
    }
});

// Item sil
function removeSectionItem(index) {
    const container = document.getElementById('section-items-list');
    const item = container.querySelector(`[data-index="${index}"]`);
    if (item) {
        item.remove();
        // Index'leri yeniden düzenle
        container.querySelectorAll('.section-item').forEach((el, i) => {
            el.dataset.index = i;
        });
    }
}

// Modalı kapat
function closeSectionModal() {
    document.getElementById('section-edit-modal').classList.add('hidden');
    currentEditingSection = null;
}

// Bekleyen section değişiklikleri (Yayınla'ya basılana kadar sadece önizlemede görünür)
window.pendingSections = {};

// Section verisini kaydet (sadece önizleme için - Yayınla'ya basılınca veritabanına kaydedilir)
function saveSectionData() {
    const sectionId = document.getElementById('section-section-id').value;
    const pageType = document.getElementById('section-page-type').value;
    
    // Section verisini topla
    const sectionData = {
        id: document.getElementById('section-db-id').value || null,
        page_type: pageType,
        section_id: sectionId,
        section_component: document.getElementById('section-component').value,
        title: document.getElementById('section-title').value,
        subtitle: document.getElementById('section-subtitle').value,
        content: document.getElementById('section-content').value,
        settings: {
            button_text: document.getElementById('section-button-text').value,
            button_link: document.getElementById('section-button-link').value
        },
        items: []
    };
    
    // Hero için ekstra ayarlar
    if (sectionId === 'hero') {
        sectionData.settings.secondary_button_text = document.getElementById('section-secondary-button-text').value;
        sectionData.settings.secondary_button_link = document.getElementById('section-secondary-button-link').value;
        sectionData.settings.background_image = document.getElementById('section-background-image').value;
        sectionData.settings.overlay_color = document.getElementById('section-overlay-color').value;
        sectionData.settings.overlay_opacity = document.getElementById('section-overlay-opacity').value;
        
        // Hero istatistikler
        document.querySelectorAll('#section-stats-list .stat-item').forEach(item => {
            sectionData.items.push({
                value: item.querySelector('.stat-value')?.value || '',
                label: item.querySelector('.stat-label')?.value || ''
            });
        });
    } else if (sectionId === 'about') {
        // About için özel ayarlar
        sectionData.settings.image = document.getElementById('section-about-image').value;
        sectionData.settings.badge_value = document.getElementById('section-badge-value').value;
        sectionData.settings.badge_label = document.getElementById('section-badge-label').value;
        
        // About özellik listesi
        document.querySelectorAll('#about-features-list .about-feature-item').forEach(item => {
            sectionData.items.push({
                text: item.querySelector('.feature-text')?.value || ''
            });
        });
    } else if (sectionId === 'cta') {
        // CTA için form ayarları
        sectionData.settings.show_form = document.getElementById('section-show-form').checked;
        sectionData.settings.form_id = document.getElementById('section-form-id').value || null;
    } else {
        // Diğer section'lar için items
        document.querySelectorAll('#section-items-list .section-item').forEach(item => {
            sectionData.items.push({
                title: item.querySelector('.item-title')?.value || '',
                description: item.querySelector('.item-desc')?.value || '',
                icon: item.querySelector('.item-icon')?.value || ''
            });
        });
    }
    
    // Bekleyen değişikliklere ekle
    const key = pageType + '_' + sectionId;
    window.pendingSections[key] = sectionData;
    
    // Değişiklik var olarak işaretle
    hasChanges = true;
    document.getElementById('save-settings').disabled = false;
    
    closeSectionModal();
    refreshPreview();
    
    // Bildirim
    showNotification('Değişiklikler önizlemeye uygulandı. Yayınlamak için "Yayınla" butonuna tıklayın.', 'info');
}

// Bekleyen tüm section'ları veritabanına kaydet
async function publishPendingSections() {
    const keys = Object.keys(window.pendingSections);
    if (keys.length === 0) return true;
    
    for (const key of keys) {
        const sectionData = window.pendingSections[key];
        
        const formData = new FormData();
        formData.append('id', sectionData.id || '');
        formData.append('page_type', sectionData.page_type);
        formData.append('section_id', sectionData.section_id);
        formData.append('section_component', sectionData.section_component);
        formData.append('title', sectionData.title);
        formData.append('subtitle', sectionData.subtitle);
        formData.append('content', sectionData.content);
        formData.append('settings', JSON.stringify(sectionData.settings));
        formData.append('items', JSON.stringify(sectionData.items));
        
        try {
            const response = await fetch('<?php echo admin_url('themes/saveSection'); ?>', {
                method: 'POST',
                body: formData
            });
            
            if (response.status === 401) {
                showNotification('Oturum süresi dolmuş. Sayfa yenileniyor...', 'error');
                setTimeout(() => window.location.reload(), 2000);
                return false;
            }
            
            const responseText = await response.text();
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Section kaydetme hatası:', responseText);
                return false;
            }
            
            if (data.success && data.section_id) {
                // DB ID'yi güncelle
                const sectionItem = document.querySelector(`[data-section-id="${sectionData.section_id}"]`);
                if (sectionItem) {
                    sectionItem.dataset.dbId = data.section_id;
                }
                // Pending'den kaldır
                window.pendingSections[key].id = data.section_id;
            }
        } catch (error) {
            console.error('Section kaydetme exception:', error);
            return false;
        }
    }
    
    // Tüm bekleyen değişiklikleri temizle
    window.pendingSections = {};
    return true;
}

// Bildirim göster
function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-emerald-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-up`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Logo Media Picker
function openThemeLogoPicker() {
    if (typeof openMediaPicker === 'function') {
        openMediaPicker({
            type: 'image',
            onSelect: function(media) {
                document.getElementById('theme-site-logo').value = media.file_url;
                document.getElementById('theme-logo-preview').innerHTML = `<img src="${media.file_url}" alt="Logo" class="max-w-full max-h-full object-contain">`;
                hasChanges = true;
                document.getElementById('save-settings').disabled = false;
                refreshPreview();
            }
        });
    } else {
        showNotification('Media Picker yüklenemedi', 'error');
    }
}

function removeThemeLogo() {
    document.getElementById('theme-site-logo').value = '';
    document.getElementById('theme-logo-preview').innerHTML = '<span class="material-symbols-outlined text-gray-400 text-2xl">image</span>';
    hasChanges = true;
    document.getElementById('save-settings').disabled = false;
    refreshPreview();
}

// Favicon Media Picker
function openThemeFaviconPicker() {
    if (typeof openMediaPicker === 'function') {
        openMediaPicker({
            type: 'image',
            onSelect: function(media) {
                document.getElementById('theme-site-favicon').value = media.file_url;
                document.getElementById('theme-favicon-preview').innerHTML = `<img src="${media.file_url}" alt="Favicon" class="max-w-full max-h-full object-contain">`;
                hasChanges = true;
                document.getElementById('save-settings').disabled = false;
                refreshPreview();
            }
        });
    } else {
        showNotification('Media Picker yüklenemedi', 'error');
    }
}

function removeThemeFavicon() {
    document.getElementById('theme-site-favicon').value = '';
    document.getElementById('theme-favicon-preview').innerHTML = '<span class="material-symbols-outlined text-gray-400 text-xl">image</span>';
    hasChanges = true;
    document.getElementById('save-settings').disabled = false;
    refreshPreview();
}

// Hero Arka Plan Görseli Picker
function openHeroBgPicker() {
    if (typeof openMediaPicker === 'function') {
        openMediaPicker({
            type: 'image',
            onSelect: function(media) {
                document.getElementById('section-background-image').value = media.file_url;
                updateHeroBgPreview(media.file_url);
            }
        });
    } else {
        showNotification('Media Picker yüklenemedi', 'error');
    }
}

function removeHeroBg() {
    document.getElementById('section-background-image').value = '';
    updateHeroBgPreview('');
}

function updateHeroBgPreview(url) {
    const preview = document.getElementById('hero-bg-preview');
    if (url) {
        preview.innerHTML = `<img src="${url}" alt="Arka Plan" class="w-full h-full object-cover">`;
    } else {
        preview.innerHTML = '<span class="material-symbols-outlined text-gray-400 text-2xl">image</span>';
    }
}

// Hero İstatistik fonksiyonları
function renderStatsItems(items) {
    const list = document.getElementById('section-stats-list');
    list.innerHTML = '';
    
    items.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'stat-item flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg';
        div.innerHTML = `
            <div class="flex-1 grid grid-cols-2 gap-3">
                <input type="text" class="stat-value px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" 
                    placeholder="Değer (ör: 500+)" value="${item.value || ''}">
                <input type="text" class="stat-label px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" 
                    placeholder="Etiket (ör: Mutlu Müşteri)" value="${item.label || ''}">
            </div>
            <button type="button" onclick="this.closest('.stat-item').remove()" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-xl">delete</span>
            </button>
        `;
        list.appendChild(div);
    });
}

function addStatItem() {
    const list = document.getElementById('section-stats-list');
    const div = document.createElement('div');
    div.className = 'stat-item flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg';
    div.innerHTML = `
        <div class="flex-1 grid grid-cols-2 gap-3">
            <input type="text" class="stat-value px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" 
                placeholder="Değer (ör: 500+)" value="">
            <input type="text" class="stat-label px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" 
                placeholder="Etiket (ör: Mutlu Müşteri)" value="">
        </div>
        <button type="button" onclick="this.closest('.stat-item').remove()" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-xl">delete</span>
        </button>
    `;
    list.appendChild(div);
}

// About Section fonksiyonları
function openAboutImagePicker() {
    if (typeof openMediaPicker === 'function') {
        openMediaPicker({
            type: 'image',
            onSelect: function(media) {
                document.getElementById('section-about-image').value = media.file_url;
                updateAboutImagePreview(media.file_url);
            }
        });
    } else {
        showNotification('Media Picker yüklenemedi', 'error');
    }
}

function removeAboutImage() {
    document.getElementById('section-about-image').value = '';
    updateAboutImagePreview('');
}

function updateAboutImagePreview(url) {
    const preview = document.getElementById('about-image-preview');
    if (url) {
        preview.innerHTML = `<img src="${url}" alt="Hakkımızda" class="w-full h-full object-cover">`;
    } else {
        preview.innerHTML = '<span class="material-symbols-outlined text-gray-400 text-2xl">image</span>';
    }
}

function renderAboutFeatures(items) {
    const list = document.getElementById('about-features-list');
    list.innerHTML = '';
    
    items.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'about-feature-item flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg';
        div.innerHTML = `
            <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
            <input type="text" class="feature-text flex-1 px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" 
                placeholder="Özellik metni" value="${item.text || ''}">
            <button type="button" onclick="this.closest('.about-feature-item').remove()" class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        `;
        list.appendChild(div);
    });
}

function addAboutFeature() {
    const list = document.getElementById('about-features-list');
    const div = document.createElement('div');
    div.className = 'about-feature-item flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg';
    div.innerHTML = `
        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
        <input type="text" class="feature-text flex-1 px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm" 
            placeholder="Özellik metni" value="">
        <button type="button" onclick="this.closest('.about-feature-item').remove()" class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    `;
    list.appendChild(div);
}

// CTA Form Display Toggle
function toggleCtaFormDisplay() {
    const showForm = document.getElementById('section-show-form').checked;
    const formSelectWrapper = document.getElementById('section-form-select-wrapper');
    
    if (showForm) {
        formSelectWrapper.classList.remove('hidden');
    } else {
        formSelectWrapper.classList.add('hidden');
    }
}

// Customizer Sidebar Toggle (Mobile)
function toggleCustomizerSidebar() {
    const sidebar = document.getElementById('customizer-sidebar');
    const overlay = document.getElementById('customizer-overlay');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// CSS animasyon
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-up {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up { animation: slide-up 0.3s ease-out; }
    
    @media (max-width: 1023px) {
        #customizer-sidebar {
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }
    }
`;
document.head.appendChild(style);
</script>

<!-- Media Picker JS -->
<script src="<?php echo rtrim(site_url(), '/') . '/admin/js/media-picker.js'; ?>"></script>

</body>
</html>

