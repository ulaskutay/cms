<?php
/**
 * DeepL API Service
 * 
 * DeepL API ile çeviri işlemleri
 */

class DeepLService {
    
    private $apiKey;
    private $apiUrl;
    
    public function __construct() {
        $settings = get_module_settings('translation');
        $this->apiKey = $settings['deepl_api_key'] ?? '';
        $this->apiUrl = $settings['deepl_api_url'] ?? 'https://api-free.deepl.com/v2/translate';
    }
    
    /**
     * Metni çevir
     * 
     * @param string $text Çevrilecek metin
     * @param string $sourceLang Kaynak dil kodu
     * @param string $targetLang Hedef dil kodu
     * @param bool $isHtml HTML içerik mi? (default: auto-detect)
     */
    public function translate($text, $sourceLang, $targetLang, $isHtml = null) {
        if (empty($this->apiKey)) {
            error_log("DeepL API key not configured - Please set API key in translation module settings");
            return false;
        }
        
        if (empty($text)) {
            return '';
        }
        
        // Debug: Çok kısa metinler için log
        if (strlen(trim($text)) < 3) {
            error_log("DeepL: Skipping very short text: " . substr($text, 0, 20));
        }
        
        // DeepL dil kodlarını dönüştür
        $sourceLang = $this->convertLanguageCode($sourceLang);
        $targetLang = $this->convertLanguageCode($targetLang);
        
        // HTML içerik tespiti (otomatik veya manuel)
        if ($isHtml === null) {
            $isHtml = $this->isHtmlContent($text);
        }
        
        $data = [
            'text' => $text,
            'source_lang' => $sourceLang,
            'target_lang' => $targetLang,
            'auth_key' => $this->apiKey
        ];
        
        // HTML içerik ise tag koruma parametresi ekle
        if ($isHtml) {
            $data['tag_handling'] = 'html';
            $data['preserve_formatting'] = '1';
        }
        
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if (!empty($curlError)) {
            error_log("DeepL API curl error: " . $curlError);
            return false;
        }
        
        if ($httpCode !== 200) {
            error_log("DeepL API error: HTTP $httpCode - Response: " . substr($response, 0, 200));
            // API key hatası mı kontrol et
            if ($httpCode === 403) {
                error_log("DeepL API: Authentication failed - Check your API key");
            } elseif ($httpCode === 456) {
                error_log("DeepL API: Quota exceeded - Check your usage limits");
            }
            return false;
        }
        
        $result = json_decode($response, true);
        
        if (isset($result['translations'][0]['text'])) {
            $translated = $result['translations'][0]['text'];
            // Debug: Çeviri başarılı
            if ($translated !== $text) {
                error_log("DeepL: Translation successful - '" . substr($text, 0, 30) . "' -> '" . substr($translated, 0, 30) . "'");
            }
            return $translated;
        }
        
        error_log("DeepL API error: Invalid response structure - " . substr($response, 0, 200));
        return false;
    }
    
    /**
     * İçeriğin HTML olup olmadığını tespit et
     * 
     * @param string $text Kontrol edilecek metin
     * @return bool HTML içerik ise true
     */
    private function isHtmlContent($text) {
        if (empty($text)) {
            return false;
        }
        
        // HTML tag'leri içeriyor mu kontrol et (<tag> veya </tag>)
        if (preg_match('/<[^>]+>/', $text)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Dil kodunu DeepL formatına çevir
     */
    private function convertLanguageCode($code) {
        $map = [
            'tr' => 'TR',
            'en' => 'EN',
            'de' => 'DE',
            'fr' => 'FR',
            'es' => 'ES',
            'it' => 'IT',
            'pt' => 'PT',
            'ru' => 'RU',
            'ja' => 'JA',
            'zh' => 'ZH',
            'pl' => 'PL',
            'nl' => 'NL',
            'sv' => 'SV',
            'da' => 'DA',
            'fi' => 'FI',
            'el' => 'EL',
            'cs' => 'CS',
            'ro' => 'RO',
            'hu' => 'HU',
            'bg' => 'BG',
            'sk' => 'SK',
            'sl' => 'SL',
            'et' => 'ET',
            'lv' => 'LV',
            'lt' => 'LT',
            'uk' => 'UK'
        ];
        
        return $map[strtolower($code)] ?? strtoupper($code);
    }
    
    /**
     * API kullanım limitini kontrol et
     */
    public function checkUsage() {
        if (empty($this->apiKey)) {
            return false;
        }
        
        $usageUrl = 'https://api-free.deepl.com/v2/usage?auth_key=' . $this->apiKey;
        
        $ch = curl_init($usageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            return json_decode($response, true);
        }
        
        return false;
    }
}
