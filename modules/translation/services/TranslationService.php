<?php
/**
 * Translation Service
 * 
 * Core translation logic - handles text translation, caching, and database operations
 */

require_once __DIR__ . '/../models/TranslationModel.php';

class TranslationService {
    
    private $model;
    private $languageService;
    private $settings;
    private $cache = [];
    
    public function __construct($languageService, $settings, $model = null) {
        $this->languageService = $languageService;
        $this->settings = $settings;
        $this->model = $model; // Model'i direkt al (eğer verilmişse)
        // Model yoksa lazy load edilecek (ensureModel'de)
    }
    
    /**
     * Ensure model is initialized
     */
    private function ensureModel() {
        if (!$this->model) {
            $this->model = new TranslationModel();
        }
    }
    
    /**
     * Translate text - Main entry point for translation
     * 
     * @param string $text Text to translate
     * @param string $domain Text domain (for future use, currently not used)
     * @return string Translated text or original if translation not found
     */
    public function translate($text, $domain = 'default') {
        if (empty($text) || !is_string($text)) {
            return $text;
        }
        
        // LanguageService'in detectLanguage() çağrıldığından emin ol
        if (!$this->languageService) {
            return $text;
        }
        
        $currentLang = $this->languageService->getCurrentLanguage();
        $defaultLang = $this->languageService->getDefaultLanguage();
        
        // Varsayılan dil ise çevirme - bu normal
        if ($currentLang === $defaultLang) {
            return $text;
        }
        
        // İçeriği normalize et
        $normalizedText = $this->normalizeText($text);
        if (empty($normalizedText)) {
            return $text;
        }
        
        // Cache kontrolü
        $cacheKey = md5($normalizedText . $currentLang);
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }
        
        // Metin tipini belirle (title veya content)
        // Kısa metinler (100 karakterden az) title, uzun metinler content olarak işaretlenir
        $textType = (strlen($normalizedText) <= 100) ? 'title' : 'content';
        
        // HTML içerik kontrolü - HTML içeriyorsa content olarak işaretle
        if ($this->isHtmlContent($normalizedText)) {
            $textType = 'content';
        }
        
        // Hash oluştur (bulk translate ile aynı - trim edilmiş metinden)
        $textHash = md5($normalizedText);
        
        // Veritabanından çeviriyi getir
        $translation = $this->getTranslation($textType, $textHash, $currentLang, $normalizedText);
        
        if ($translation && !empty($translation['translated_text'])) {
            $this->cache[$cacheKey] = $translation['translated_text'];
            return $translation['translated_text'];
        }
        
        // DEBUG: Çeviri bulunamadı - log'la (HER ZAMAN - frontend'de çeviri çalışmıyor)
        error_log("TranslationService: Translation not found - text='$normalizedText', type='$textType', lang='$currentLang', hash='$textHash', defaultLang='$defaultLang'");
        
        // Veritabanında bu hash ile çeviri var mı kontrol et (debug için)
        $this->ensureModel();
        $anyTranslation = $this->model->getTranslation($textType, $textHash, $currentLang, $normalizedText);
        if (!$anyTranslation) {
            // Farklı type ile dene
            $altType = ($textType === 'title') ? 'content' : 'title';
            $anyTranslation = $this->model->getTranslation($altType, $textHash, $currentLang, $normalizedText);
            if ($anyTranslation) {
                error_log("TranslationService: Found translation with different type ($altType instead of $textType)");
            } else {
                // Veritabanında bu hash ile hiç çeviri var mı?
                $db = Database::getInstance();
                $checkHash = $db->fetch("SELECT COUNT(*) as cnt FROM translations WHERE source_id = ?", [$textHash]);
                if ($checkHash && $checkHash['cnt'] > 0) {
                    error_log("TranslationService: Hash exists in DB but not for language '$currentLang' (found " . $checkHash['cnt'] . " translations with this hash)");
                    // Hangi dillerde var?
                    $langs = $db->fetchAll("SELECT DISTINCT target_language FROM translations WHERE source_id = ?", [$textHash]);
                    $langList = array_column($langs, 'target_language');
                    error_log("TranslationService: Available languages for this hash: " . implode(', ', $langList));
                } else {
                    error_log("TranslationService: Hash '$textHash' does not exist in database at all");
                }
            }
        }
        
        // Çeviri yoksa orijinal metni döndür
        $this->cache[$cacheKey] = $text;
        return $text;
    }
    
    /**
     * Get translation from database
     * 
     * @param string $type Translation type (title/content)
     * @param string $sourceId Source text hash
     * @param string $targetLanguage Target language code
     * @param string|null $sourceText Optional source text for fallback lookup
     * @return array|null Translation data or null
     */
    public function getTranslation($type, $sourceId, $targetLanguage, $sourceText = null) {
        $this->ensureModel();
        return $this->model->getTranslation($type, $sourceId, $targetLanguage, $sourceText);
    }
    
    /**
     * Save translation to database
     * 
     * @param array $data Translation data
     * @return int|false Translation ID or false on error
     */
    public function saveTranslation($data) {
        $this->ensureModel();
        return $this->model->saveTranslation($data);
    }
    
    /**
     * Normalize text - trim and clean
     * 
     * @param string $text Text to normalize
     * @return string Normalized text
     */
    public function normalizeText($text) {
        return trim($text);
    }
    
    /**
     * Check if content is HTML
     * 
     * @param string $content Content to check
     * @return bool True if HTML content
     */
    public function isHtmlContent($content) {
        if (empty($content)) {
            return false;
        }
        
        // HTML tag'leri içeriyor mu kontrol et (<tag> veya </tag>)
        if (preg_match('/<[^>]+>/', $content)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Normalize translation type - converts various types to 'title' or 'content'
     * 
     * @param string $type Original type
     * @return string Normalized type ('title' or 'content')
     */
    public function normalizeTranslationType($type) {
        // Title grubu - kısa metinler, başlıklar
        $titleTypes = [
            'title', 'subtitle', 'label', 'name', 'badge', 'button', 'heading',
            'menu_name', 'menu_item_title', 'slider_name', 'slider_item_title',
            'slider_item_subtitle', 'slider_item_button', 'form_name', 'form_button',
            'form_field_label', 'form_field_placeholder', 'category_name', 'tag_name',
            'section_title', 'section_subtitle', 'section_setting', 'section_item',
            'theme_option'
        ];
        
        // Content grubu - uzun metinler, açıklamalar
        $contentTypes = [
            'content', 'description', 'excerpt', 'text', 'body',
            'menu_description', 'slider_description', 'slider_item_description',
            'slider_layer_content', 'form_description', 'form_success', 'form_error',
            'form_field_help', 'category_description', 'section_content'
        ];
        
        // Exact match kontrolü
        if (in_array($type, $titleTypes)) {
            return 'title';
        }
        if (in_array($type, $contentTypes)) {
            return 'content';
        }
        
        // Partial match - title içeren type'lar
        if (strpos($type, 'title') !== false || strpos($type, 'name') !== false || 
            strpos($type, 'label') !== false || strpos($type, 'button') !== false ||
            strpos($type, 'badge') !== false || strpos($type, 'heading') !== false) {
            return 'title';
        }
        
        // Partial match - content içeren type'lar
        if (strpos($type, 'content') !== false || strpos($type, 'description') !== false || 
            strpos($type, 'excerpt') !== false || strpos($type, 'text') !== false ||
            strpos($type, 'body') !== false) {
            return 'content';
        }
        
        // Varsayılan olarak title döndür (kısa metinler için)
        return 'title';
    }
    
    /**
     * Clear cache
     */
    public function clearCache() {
        $this->cache = [];
    }
}
