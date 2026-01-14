<?php
/**
 * GeoIP Service
 * IP adresine göre ülke tespiti yapar
 */

class GeoIPService {
    
    /**
     * IP adresinden ülke kodu al
     * @param string|null $ip IP adresi (null ise client IP)
     * @return string|null Ülke kodu (TR, DE, vb.) veya null
     */
    public function getCountryCode($ip = null) {
        if ($ip === null) {
            $ip = $this->getClientIP();
        }
        
        if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1') {
            // Localhost - test için null döndür
            return null;
        }
        
        // Cache kontrolü (1 saat cache)
        $cacheKey = 'geoip_' . md5($ip);
        $cached = $this->getCache($cacheKey);
        if ($cached !== false) {
            return $cached;
        }
        
        // Ücretsiz API: ip-api.com (ücretsiz, dakikada 45 istek limiti)
        $countryCode = $this->getFromIPAPI($ip);
        
        if ($countryCode) {
            // 1 saat cache'le
            $this->setCache($cacheKey, $countryCode, 3600);
            return $countryCode;
        }
        
        return null;
    }
    
    /**
     * Client IP adresini al
     */
    private function getClientIP() {
        $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                
                // X-Forwarded-For birden fazla IP içerebilir
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                
                // IPv6 veya geçersiz IP kontrolü
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
    
    /**
     * ip-api.com servisinden ülke kodu al
     */
    private function getFromIPAPI($ip) {
        $url = "http://ip-api.com/json/{$ip}?fields=status,countryCode";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['status']) && $data['status'] === 'success' && !empty($data['countryCode'])) {
                return $data['countryCode'];
            }
        }
        
        return null;
    }
    
    /**
     * Basit cache sistemi (dosya bazlı)
     */
    private function getCache($key) {
        $cacheFile = $this->getCacheFile($key);
        
        if (!file_exists($cacheFile)) {
            return false;
        }
        
        $data = json_decode(file_get_contents($cacheFile), true);
        
        if ($data && isset($data['expires']) && $data['expires'] > time()) {
            return $data['value'];
        }
        
        // Expire olmuş, dosyayı sil
        @unlink($cacheFile);
        return false;
    }
    
    private function setCache($key, $value, $ttl = 3600) {
        $cacheDir = __DIR__ . '/../../../../storage/cache/geoip';
        
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        
        $cacheFile = $cacheDir . '/' . $key . '.json';
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        @file_put_contents($cacheFile, json_encode($data));
    }
    
    private function getCacheFile($key) {
        $cacheDir = __DIR__ . '/../../../../storage/cache/geoip';
        return $cacheDir . '/' . $key . '.json';
    }
    
    /**
     * Ülke kodundan dil koduna dönüştür
     * @param string $countryCode Ülke kodu (TR, DE, vb.)
     * @return string Dil kodu (tr, de, en)
     */
    public function countryToLanguage($countryCode) {
        $countryLanguageMap = [
            'TR' => 'tr',
            'DE' => 'de',
            'AT' => 'de', // Avusturya
            'CH' => 'de', // İsviçre (bir kısmı)
            'GB' => 'en',
            'US' => 'en',
            'CA' => 'en',
            'AU' => 'en',
            'NZ' => 'en',
            // Diğer ülkeler için varsayılan olarak null döndür (en kullanılacak)
        ];
        
        return $countryLanguageMap[$countryCode] ?? null;
    }
}
