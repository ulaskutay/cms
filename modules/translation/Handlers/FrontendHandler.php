<?php
/**
 * Frontend Handler
 * 
 * Handles frontend translation filters, language switcher, and meta tags
 */

require_once __DIR__ . '/../services/TranslationService.php';
require_once __DIR__ . '/../services/LanguageService.php';
require_once __DIR__ . '/../models/TranslationModel.php';

class FrontendHandler {
    
    private $translationService;
    private $languageService;
    private $model;
    private $settings;
    
    public function __construct($translationService, $languageService, $model, $settings) {
        $this->translationService = $translationService;
        $this->languageService = $languageService;
        $this->model = $model;
        $this->settings = $settings;
    }
    
    /**
     * Filter content - translates content
     * 
     * @param string $content Content to translate
     * @return string Translated content
     */
    public function filterContent($content) {
        return $this->translationService->translate($content);
    }
    
    /**
     * Filter title - translates title
     * 
     * @param string $title Title to translate
     * @return string Translated title
     */
    public function filterTitle($title) {
        return $this->translationService->translate($title);
    }
    
    /**
     * Filter excerpt - translates excerpt
     * 
     * @param string $excerpt Excerpt to translate
     * @return string Translated excerpt
     */
    public function filterExcerpt($excerpt) {
        return $this->translationService->translate($excerpt);
    }
    
    /**
     * Output language meta tag
     */
    public function outputLanguageMeta() {
        $currentLang = $this->languageService->getCurrentLanguage();
        echo '<meta http-equiv="content-language" content="' . htmlspecialchars($currentLang) . '">' . "\n";
    }
    
    /**
     * Render language switcher
     */
    public function renderLanguageSwitcher() {
        $languages = $this->model->getActiveLanguages();
        if (empty($languages) || count($languages) < 2) {
            return; // Sadece 1 dil varsa gösterme
        }
        
        $currentUrl = $this->getCurrentUrl();
        $currentPath = parse_url($currentUrl, PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        
        // Mevcut dil kodunu URL'den çıkar
        $basePath = $currentPath;
        if (!empty($pathParts[0]) && strlen($pathParts[0]) === 2 && $this->model->isValidLanguage($pathParts[0])) {
            array_shift($pathParts);
            $basePath = '/' . implode('/', $pathParts);
        }
        
        // basePath'i normalize et
        $basePath = rtrim($basePath, '/');
        if (empty($basePath)) {
            $basePath = '';
        }
        
        $defaultLang = $this->settings['default_language'] ?? 'tr';
        $currentLang = $this->languageService->getCurrentLanguage();
        
        // Unique ID oluştur (desktop ve mobile için farklı olmalı)
        $uniqueId = 'lang-switcher-' . uniqid();
        
        ?>
        <div class="language-switcher-wrapper relative group" data-lang-switcher-id="<?php echo esc_attr($uniqueId); ?>">
            <button type="button" class="language-switcher-btn flex items-center gap-1.5 px-3 py-2 rounded-lg transition-colors duration-300 text-[#A1A1AA] hover:text-white hover:bg-white/5" data-lang-switcher-btn="<?php echo esc_attr($uniqueId); ?>">
                <span class="text-sm font-medium"><?php echo strtoupper($currentLang); ?></span>
                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <div class="language-switcher-dropdown lg:absolute lg:right-0 lg:top-full lg:mt-2 relative lg:opacity-0 lg:invisible lg:group-hover:opacity-100 lg:group-hover:visible mt-2 bg-[#0B0B0B]/90 backdrop-blur-2xl border border-white/8 rounded-2xl shadow-xl py-2 min-w-[160px] opacity-0 invisible transition-all duration-300 ease-out z-50" data-lang-switcher-dropdown="<?php echo esc_attr($uniqueId); ?>">
                <?php foreach ($languages as $lang): ?>
                    <?php
                    // Varsayılan dil için prefix kullanma, diğerleri için /lang/path şeklinde
                    if ($lang['code'] === $defaultLang) {
                        $langUrl = $basePath ?: '/';
                    } else {
                        $langUrl = '/' . $lang['code'] . $basePath;
                        if ($langUrl === '/' . $lang['code']) {
                            $langUrl = '/' . $lang['code'] . '/';
                        }
                    }
                    $isActive = $lang['code'] === $currentLang;
                    ?>
                    <a href="<?php echo htmlspecialchars($langUrl); ?>" 
                       class="flex items-center gap-2.5 px-4 py-2.5 text-[#A1A1AA] hover:text-white hover:bg-white/5 transition-colors duration-300 <?php echo $isActive ? 'bg-white/5 text-white font-medium' : ''; ?>"
                       data-lang="<?php echo $lang['code']; ?>">
                        <span class="text-lg"><?php echo htmlspecialchars($lang['flag'] ?? ''); ?></span>
                        <span class="text-sm"><?php echo htmlspecialchars($lang['native_name'] ?? $lang['name']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        (function() {
            'use strict';
            
            // Unique ID ile switcher'ı bul
            const switcherId = '<?php echo esc_js($uniqueId); ?>';
            const wrapper = document.querySelector('[data-lang-switcher-id="' + switcherId + '"]');
            if (!wrapper) return;
            
            const btn = wrapper.querySelector('[data-lang-switcher-btn="' + switcherId + '"]');
            const dropdown = wrapper.querySelector('[data-lang-switcher-dropdown="' + switcherId + '"]');
            
            if (!btn || !dropdown) return;
            
            // Mobile için dropdown toggle
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isHidden = dropdown.classList.contains('opacity-0') || dropdown.classList.contains('invisible');
                
                if (isHidden) {
                    dropdown.classList.remove('opacity-0', 'invisible');
                } else {
                    dropdown.classList.add('opacity-0', 'invisible');
                }
            });
            
            // Dışarı tıklandığında kapat
            document.addEventListener('click', function(e) {
                if (wrapper && !wrapper.contains(e.target)) {
                    dropdown.classList.add('opacity-0', 'invisible');
                }
            });
        })();
        </script>
        <?php
    }
    
    /**
     * Shortcode: Language switcher
     * 
     * @param array $atts Shortcode attributes
     * @return string Language switcher HTML
     */
    public function shortcodeLanguageSwitcher($atts = []) {
        $languages = $this->model->getActiveLanguages();
        $currentUrl = $this->getCurrentUrl();
        $currentPath = parse_url($currentUrl, PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        
        // Mevcut dil kodunu URL'den çıkar
        $basePath = $currentPath;
        if (!empty($pathParts[0]) && strlen($pathParts[0]) === 2 && $this->model->isValidLanguage($pathParts[0])) {
            array_shift($pathParts);
            $basePath = '/' . implode('/', $pathParts);
        }
        
        ob_start();
        ?>
        <div class="language-switcher flex gap-2">
            <?php foreach ($languages as $lang): ?>
                <?php
                $langUrl = ($lang['code'] === ($this->settings['default_language'] ?? 'tr')) 
                    ? $basePath 
                    : '/' . $lang['code'] . $basePath;
                ?>
                <a href="<?php echo htmlspecialchars($langUrl); ?>" 
                   class="lang-link px-3 py-1 rounded <?php echo $lang['code'] === $this->languageService->getCurrentLanguage() ? 'bg-blue-500 text-white' : 'bg-gray-200'; ?>"
                   data-lang="<?php echo $lang['code']; ?>">
                    <?php echo htmlspecialchars($lang['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get current URL
     * 
     * @return string Current URL
     */
    private function getCurrentUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        return $protocol . '://' . $host . $path;
    }
    
    /**
     * Filter section settings recursively
     * 
     * @param array $settings Settings array
     * @param string $sectionId Section ID
     * @return array Translated settings
     */
    public function filterSectionSettings($settings, $sectionId) {
        if (!is_array($settings)) {
            return $settings;
        }
        
        $currentLang = $this->languageService->getCurrentLanguage();
        $defaultLang = $this->languageService->getDefaultLanguage();
        
        // Varsayılan dil ise çevirme
        if ($currentLang === $defaultLang) {
            return $settings;
        }
        
        foreach ($settings as $key => $value) {
            // Çevirilmemesi gereken key'leri atla
            if (in_array($key, ['icon', 'css', 'url', 'gradient', 'color', 'bg_color', 'text_color', 'border_color', 'image', 'video', 'link', 'target', 'class', 'id', 'style', 'width', 'height', 'size', 'position', 'alignment', 'animation', 'delay', 'duration', 'easing', 'opacity', 'z_index', 'margin', 'padding', 'border', 'border_radius', 'box_shadow', 'text_shadow', 'font_family', 'font_size', 'font_weight', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration', 'text_align', 'vertical_align', 'display', 'flex_direction', 'flex_wrap', 'justify_content', 'align_items', 'align_self', 'gap', 'order', 'flex', 'flex_grow', 'flex_shrink', 'flex_basis', 'grid_template_columns', 'grid_template_rows', 'grid_column', 'grid_row', 'grid_area', 'grid_gap', 'object_fit', 'object_position', 'overflow', 'overflow_x', 'overflow_y', 'cursor', 'pointer_events', 'user_select', 'visibility', 'transform', 'transform_origin', 'backface_visibility', 'perspective', 'perspective_origin', 'transform_style', 'transition', 'transition_property', 'transition_duration', 'transition_timing_function', 'transition_delay', 'animation', 'animation_name', 'animation_duration', 'animation_timing_function', 'animation_delay', 'animation_iteration_count', 'animation_direction', 'animation_fill_mode', 'animation_play_state', 'will_change', 'contain', 'isolation', 'mix_blend_mode', 'filter', 'backdrop_filter', 'clip_path', 'mask', 'mask_image', 'mask_mode', 'mask_repeat', 'mask_position', 'mask_size', 'mask_origin', 'mask_clip', 'mask_composite', 'mask_type', 'mask_border', 'mask_border_source', 'mask_border_slice', 'mask_border_width', 'mask_border_outset', 'mask_border_repeat', 'mask_border_mode', 'mask_box_image', 'mask_box_image_source', 'mask_box_image_slice', 'mask_box_image_width', 'mask_box_image_outset', 'mask_box_image_repeat', 'mask_box_image_mode'])) {
                continue;
            }
            
            if (is_string($value) && !empty($value)) {
                $settings[$key] = $this->translationService->translate($value);
            } elseif (is_array($value)) {
                $settings[$key] = $this->filterSectionSettings($value, $sectionId);
            }
        }
        
        return $settings;
    }
    
    /**
     * Filter section items recursively
     * 
     * @param array $items Items array
     * @param string $sectionId Section ID
     * @return array Translated items
     */
    public function filterSectionItems($items, $sectionId) {
        if (!is_array($items)) {
            return $items;
        }
        
        $currentLang = $this->languageService->getCurrentLanguage();
        $defaultLang = $this->languageService->getDefaultLanguage();
        
        // Varsayılan dil ise çevirme
        if ($currentLang === $defaultLang) {
            return $items;
        }
        
        foreach ($items as &$item) {
            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    // Çevirilmemesi gereken key'leri atla
                    if (in_array($key, ['icon', 'css', 'url', 'gradient', 'color', 'image', 'video', 'link', 'target', 'class', 'id', 'style'])) {
                        continue;
                    }
                    
                    if (is_string($value) && !empty($value)) {
                        $item[$key] = $this->translationService->translate($value);
                    } elseif (is_array($value)) {
                        $item[$key] = $this->filterSectionItems($value, $sectionId);
                    }
                }
            }
        }
        
        return $items;
    }
}
